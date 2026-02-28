<?php

namespace App\Http\Controllers;

use App\Models\Regle;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request, Regle $regle)
    {
        $colocation = $regle->colocation;

        if ($request->user()->id !== $regle->from_user_id && $request->user()->id !== $colocation->owner_id) {
            abort(403);
        }

        if ($regle->paid_at) {
            return back()->with('error', 'Déjà payé.');
        }

        $regle->update(['paid_at' => now()]);

        return back()->with('success', 'Paiement marqué comme payé.');
    }
}

