<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\User;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function leave(Request $request, Colocation $colocation)
    {
        $user = $request->user();

        // Owner ne peut pas quitter (selon ton cahier)
        if ($colocation->owner_id === $user->id) {
            return back()->with('error', "L'owner ne peut pas quitter la colocation.");
        }

        // doit être membre accepté et actif
        $isActiveMember = $colocation->members()
            ->where('users.id', $user->id)
            ->wherePivot('status', 'accepted')
            ->wherePivot('left_at', null)
            ->exists();

        if (!$isActiveMember) {
            return back()->with('error', "Vous n'êtes pas membre actif de cette colocation.");
        }

        // TODO (branché après balances): calculer si ce user a une dette
        $hasDebt = $this->hasDebt($user, $colocation);

        // marquer départ
        $colocation->members()->updateExistingPivot($user->id, [
            'left_at' => now(),
        ]);

        // réputation (+1 / -1)
        $this->updateReputation($user, $hasDebt);

        return redirect()->route('dashboard')->with('success', 'Vous avez quitté la colocation.');
    }

    public function remove(Request $request, Colocation $colocation, User $user)
    {
        // sécurité owner
        if ($colocation->owner_id !== $request->user()->id) {
            abort(403);
        }

        // owner ne peut pas se retirer lui-même
        if ($user->id === $colocation->owner_id) {
            return back()->with('error', "Vous ne pouvez pas retirer l'owner.");
        }

        $isActiveMember = $colocation->members()
            ->where('users.id', $user->id)
            ->wherePivot('status', 'accepted')
            ->wherePivot('left_at', null)
            ->exists();

        if (!$isActiveMember) {
            return back()->with('error', "Ce user n'est pas un membre actif.");
        }

        // TODO (branché après balances): dette du membre
        $hasDebt = $this->hasDebt($user, $colocation);

        // règle spéciale: si owner retire un membre avec dette => dette imputée à l’owner
        // TODO: on fera l’ajustement interne après balances/settlements.
        // Pour l’instant, on applique juste la pénalité réputation.
        $colocation->members()->updateExistingPivot($user->id, [
            'left_at' => now(),
        ]);

        $this->updateReputation($user, $hasDebt);

        return back()->with('success', 'Membre retiré.');
    }

    private function hasDebt(User $user, Colocation $colocation): bool
    {
        // TODO: après Module 3 (balances), on calculera le solde du user.
        // Pour l’instant, on retourne false pour ne pas casser.
        return false;
    }

    private function updateReputation(User $user, bool $hasDebt): void
    {
        // ⚠️ Remplace "reputation_score" par ton vrai champ
        $field = 'reputation_score';

        if (!array_key_exists($field, $user->getAttributes())) {
            // si le champ n'existe pas, on ne fait rien (évite crash)
            return;
        }

        $delta = $hasDebt ? -1 : +1;
        $user->update([$field => ($user->$field ?? 0) + $delta]);
    }
}