<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $colocation = null;

        // 1) Si user est owner : sa colocation active
        if ($user->role === 'owner') {
            $colocation = Colocation::where('owner_id', $user->id)
                ->where('status', 'active')
                ->first();
        }

        // 2) Sinon : colocation via pivot acceptée
        if (!$colocation) {
            $colocation = $user->colocations()
                ->wherePivot('status', 'accepted')
                ->wherePivot('left_at', null)
                ->where('colocations.status', 'active')
                ->first();
        }

        // Charger les membres (acceptés + actifs) si colocation trouvée
        if ($colocation) {
            $colocation->load([
                'owner',
                'members' => function ($q) {
                    $q->wherePivot('status', 'accepted')
                      ->wherePivot('left_at', null);
                }
            ]);
        }

        return view('dashboard', compact('colocation'));
    }
}