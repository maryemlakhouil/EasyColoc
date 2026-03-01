<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Http\Requests\StoreColocationRequest;   
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\SettlementService;
use App\Models\Regle;


class ColocationController extends Controller
{
    public function create()
    {
        return view('colocations.create');
    }

    public function store(StoreColocationRequest $request)
    {
        $user = $request->user();   

        // Règle: un user ne peut pas créer une nouvelle colocation s'il en a déjà une active (owner)

        $alreadyOwnerActive = $user->colocationsOwned()->where('status', 'active')->exists();
        if ($alreadyOwnerActive) {
            return back()->with('error', 'Vous avez déjà une colocation active.');
        }

        $colocation = Colocation::create([
            'name' => $request->validated()['name'],
            'owner_id' => $user->id,
            'status' => 'active',
        ]);

       $defaultCategories = [
            'Général',
            'Loyer',
            'Électricité',
            'Eau',
            'Internet',
            'Courses',
            'Transport',
            'Entretien',
        ];

        foreach ($defaultCategories as $catName) {
            \App\Models\Category::firstOrCreate([
                'colocation_id' => $colocation->id,
                'name' => $catName,
            ]);
        }

        // On peut aussi mettre son role en "owner" ici 
        // mais ça sera mieux quand on aura Membership. Pour l’instant simple :
        if ($user->role === 'member') {
            $user->update(['role' => 'owner']);
        }

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Colocation créée avec succès.');
    }

    public function show(Colocation $colocation)
    {
        $colocation->load([
        'owner',
        'members' => function ($q) {
            $q->wherePivot('status', 'accepted')
              ->wherePivot('left_at', null);
        }
    ]);

        $usersEmails = User::orderBy('email')->get(['id', 'email']);
        $openSettlements = Regle::where('colocation_id', $colocation->id)
        ->whereNull('paid_at')
        ->with(['fromUser', 'toUser'])
        ->orderBy('id', 'desc')
        ->get();

        return view('colocations.show', compact('colocation', 'usersEmails', 'openSettlements'));
    }

    public function my()
    {
        $user = auth()->user();

        // si owner: sa colocation active
        if ($user->role === 'owner') {
            $colocation = Colocation::where('owner_id', $user->id)
                ->where('status', 'active')
                ->first();

            if ($colocation) {
                return redirect()->route('colocations.show', $colocation);
            }
        }

        // si member: colocation acceptée active (pivot)
        $colocation = $user->colocations()
            ->wherePivot('status', 'accepted')
            ->wherePivot('left_at', null)
            ->where('colocations.status', 'active')
            ->first();

        if ($colocation) {
            return redirect()->route('colocations.show', $colocation);
        }

        return redirect()->route('colocations.create')
            ->with('error', 'Vous n’avez pas de colocation active.');
    }


    public function cancel(Request $request, Colocation $colocation, SettlementService $settlementService)
    {
        abort_if(auth()->id() !== $colocation->owner_id, 403);
        $settlementService->cancelColocation($colocation);
        DB::transaction(function () use ($colocation) {
            // 1) annuler la colocation
            $colocation->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // 2) faire quitter automatiquement tous les membres encore actifs
            // pivot: colocation_user (user_id, colocation_id, status, left_at, ...)
            $colocation->members()
                ->wherePivotNull('left_at')
                ->updateExistingPivot(
                    $colocation->members()->wherePivotNull('left_at')->pluck('users.id')->toArray(),
                    ['left_at' => now()]
                );

            // IMPORTANT: si ton owner n’est PAS attaché au pivot, pas besoin de le traiter.
            // Si ton owner est attaché aussi, il aura left_at rempli, et c'est OK.
        });

        return back()->with('success', 'Colocation annulée. Les membres ont quitté automatiquement.');
    }
    
    public function destroy(Colocation $colocation)
    {
        abort_if(auth()->id() !== $colocation->owner_id, 403);

        $colocation->delete(); // cascade fera le reste
        return redirect()->route('dashboard')->with('success', 'Colocation supprimée définitivement.');
    }
}



