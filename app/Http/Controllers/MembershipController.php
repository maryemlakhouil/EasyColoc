<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\SettlementService;

class MembershipController extends Controller
{
    public function leave(Request $request, Colocation $colocation, SettlementService $settlementService)
    {
        $user = $request->user();

        // Owner ne peut pas quitter
        if ($colocation->owner_id === $user->id) {
            return back()->with('error', "L'owner ne peut pas quitter la colocation.");
        }

        // doit être membre accepté et actif
        $isActiveMember = $colocation->members()
            ->where('users.id', $user->id)
            ->wherePivot('status', 'accepted')
            ->wherePivotNull('left_at')
            ->exists();

        if (!$isActiveMember) {
            return back()->with('error', "Vous n'êtes pas membre actif de cette colocation.");
        }

        // ✅ tout est géré dans le service
        $settlementService->handleMemberLeave($colocation, $user);

        return redirect()->route('dashboard')->with('success', 'Vous avez quitté la colocation.');
    }

    public function remove(Request $request, Colocation $colocation, User $user, SettlementService $settlementService)
    {
        // sécurité owner
        abort_if($request->user()->id !== $colocation->owner_id, 403);

        // owner ne peut pas se retirer lui-même
        if ($user->id === $colocation->owner_id) {
            return back()->with('error', "Vous ne pouvez pas retirer l'owner.");
        }

        // doit être membre accepté et actif
        $isActiveMember = $colocation->members()
            ->where('users.id', $user->id)
            ->wherePivot('status', 'accepted')
            ->wherePivotNull('left_at')
            ->exists();

        if (!$isActiveMember) {
            return back()->with('error', "Ce user n'est pas un membre actif.");
        }

        // ✅ tout est géré dans le service (réputation + dette -> owner + left_at)
        $settlementService->handleOwnerRemoveMember($colocation, $user);

        return back()->with('success', 'Membre retiré.');
    }
}