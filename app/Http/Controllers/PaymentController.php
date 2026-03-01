<?php

namespace App\Http\Controllers;

use App\Models\Regle;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    
    public function pay(Request $request, Regle $regle)
    {
        // Sécurité : seulement le débiteur (from_user) ou l'owner ou l'admin
        $user = $request->user();

        $isDebtor = $user->id === $regle->from_user_id;
        $isOwner  = $user->id === $regle->colocation->owner_id;
        $isAdmin  = $user->role === 'admin';

        abort_if(!($isDebtor || $isOwner || $isAdmin), 403);

        // Déjà payé -> on ne refait rien
        if ($regle->paid_at) {
            return back()->with('success', 'Déjà payé.');
        }

        $regle->update([
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Paiement enregistré.');
    }
}

