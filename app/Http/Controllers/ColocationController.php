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
    //1 -  réer une colocation

    public function create()
    {
        return view('colocations.create');
    }

    // 2 - enregistrer une nouvelle colocation 

    public function store(StoreColocationRequest $request)
    {
        $user = $request->user();   

        // cas 1 : un user ne peut pas créer une nouvelle colocation s'il en a déjà une active (owner)

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

        if ($user->role === 'member') {
            $user->update(['role' => 'owner']);
        }

        return redirect()->route('colocations.show', $colocation)->with('success', 'Colocation créée avec succès.');
    }

    // 3 - Affiche les détails d’une colocation : 

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
        // les dettes non payées 
        $openSettlements = Regle::where('colocation_id', $colocation->id)
        ->whereNull('paid_at')
        ->with(['fromUser', 'toUser'])
        ->orderBy('id', 'desc')
        ->get();

        return view('colocations.show', compact('colocation', 'usersEmails', 'openSettlements'));
    }

    // la colocation active de l’utilisateur 

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

        return redirect()->route('colocations.create')->with('error', 'Vous n’avez pas de colocation active.');
    }

    // Annule une colocation 

    public function cancel(Request $request, Colocation $colocation, SettlementService $settlementService)
    {

        abort_if(auth()->id() !== $colocation->owner_id, 403);

        $settlementService->cancelColocation($colocation);

        DB::transaction(function () use ($colocation) {
            
            // 1 - annuler la colocation
            $colocation->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // 2 - quitter automatiquement tous les membres 
                $colocation->members()
                ->wherePivotNull('left_at')
                ->updateExistingPivot(
                    $colocation->members()->wherePivotNull('left_at')->pluck('users.id')->toArray(),
                    ['left_at' => now()]
                );
        });

        return back()->with('success', 'Colocation annulée. Les membres ont quitté automatiquement.');
    }
    // 5 - supprimer colocation 

    public function destroy(Colocation $colocation)
    {
        abort_if(auth()->id() !== $colocation->owner_id, 403);

        $colocation->delete(); 
        return redirect()->route('dashboard')->with('success', 'Colocation supprimée définitivement.');
    }
}



