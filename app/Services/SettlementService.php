<?php

namespace App\Services;

use App\Models\Colocation;
use App\Models\Regle;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SettlementService
{
    public function debtAmount(int $colocationId, int $userId): float
    {
        return (float) Regle::where('colocation_id', $colocationId)
            ->where('from_user_id', $userId)
            ->whereNull('paid_at')
            ->sum('montant');
    }

    public function hasDebt(int $colocationId, int $userId): bool
    {
        return $this->debtAmount($colocationId, $userId) > 0.00001;
    }

    public function adjustReputation(User $user, int $delta): void
    {
        $user->increment('reputation_score', $delta);
    }

    public function transferDebtToOwner(Colocation $colocation, User $member): void
    {
        Regle::where('colocation_id', $colocation->id)
            ->where('from_user_id', $member->id)
            ->whereNull('paid_at')
            ->update(['from_user_id' => $colocation->owner_id]);
    }

    public function handleMemberLeave(Colocation $colocation, User $member): void
    {
        DB::transaction(function () use ($colocation, $member) {

            $debt = $this->debtAmount($colocation->id, $member->id);

            if ($debt > 0.00001) {
                $this->adjustReputation($member, -1);

                // redistribution sur les autres membres (ou transfert owner si personne)
                $this->redistributeMemberDebt($colocation, $member);
            } else {
                $this->adjustReputation($member, +1);
            }

            $colocation->members()->updateExistingPivot($member->id, ['left_at' => now()]);
        });
    }

    public function handleOwnerRemoveMember(Colocation $colocation, User $member): void
    {
        DB::transaction(function () use ($colocation, $member) {

            $debt = $this->debtAmount($colocation->id, $member->id);

            $this->adjustReputation($member, $debt > 0.00001 ? -1 : +1);

            // règle: dette du member => imputée à l’owner
            if ($debt > 0.00001) {
                $this->transferDebtToOwner($colocation, $member);
            }

            $colocation->members()->updateExistingPivot($member->id, ['left_at' => now()]);
        });
    }

    public function cancelColocation(Colocation $colocation): void
    {
        DB::transaction(function () use ($colocation) {

            $colocation->update(['status' => 'cancelled']);

            $activeMembers = $colocation->members()
                ->wherePivot('status', 'accepted')
                ->wherePivotNull('left_at')
                ->get();

            foreach ($activeMembers as $m) {
                $debt = $this->debtAmount($colocation->id, $m->id);

                $this->adjustReputation($m, $debt > 0.00001 ? -1 : +1);

                $colocation->members()->updateExistingPivot($m->id, ['left_at' => now()]);
            }
        });
    }

    private function redistributeMemberDebt(Colocation $colocation, User $leaver): void
    {
        $remaining = $colocation->members()
            ->wherePivot('status', 'accepted')
            ->wherePivotNull('left_at')
            ->where('users.id', '!=', $leaver->id)
            ->get(['users.id']);

        // s’il n’y a plus personne, dette -> owner
        if ($remaining->isEmpty()) {
            $this->transferDebtToOwner($colocation, $leaver);
            return;
        }

        $debts = Regle::where('colocation_id', $colocation->id)
            ->where('from_user_id', $leaver->id)
            ->whereNull('paid_at')
            ->get();

        foreach ($debts as $d) {
            $n = $remaining->count();
            $cents = (int) round(((float) $d->montant) * 100);

            $base = intdiv($cents, $n);
            $rest = $cents % $n;

            foreach ($remaining->values() as $idx => $m) {
                $shareCents = $base + ($idx < $rest ? 1 : 0);
                if ($shareCents <= 0) continue;

                Regle::create([
                    'colocation_id' => $colocation->id,
                    'from_user_id'  => $m->id,
                    'to_user_id'    => $d->to_user_id,
                    'montant'       => $shareCents / 100,
                ]);
            }
        }

        // supprimer les dettes du quittant
        Regle::where('colocation_id', $colocation->id)
            ->where('from_user_id', $leaver->id)
            ->whereNull('paid_at')
            ->delete();
    }
}