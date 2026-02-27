<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Http\Requests\StoreColocationRequest;   
use App\Models\User;

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
        \App\Models\Category::firstOrCreate([
            'colocation_id' => $colocation->id,
            'name' => 'Général',
        ]);

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

        return view('colocations.show', compact('colocation', 'usersEmails'));
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
 
}



