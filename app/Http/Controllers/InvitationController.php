<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;    
use App\Mail\ColocationInvitationMail;
use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    // OWNER envoie invitation
    public function store(Request $request, Colocation $colocation)
    {
        // sécurité: owner doit être le créateur de la colocation
        if ($colocation->owner_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // éviter inviter quelqu’un déjà membre accepté (et non parti)
        $existingUser = User::where('email', $data['email'])->first();

        if ($existingUser) {
            $alreadyInThisColoc = $colocation->members()
                ->where('users.id', $existingUser->id)
                ->wherePivot('left_at', null)
                ->exists();

            if ($alreadyInThisColoc) {
                return back()->with('error', 'Cet utilisateur est déjà membre de la colocation.');
            }
        }

        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'invited_by' => $request->user()->id,
            'invited_email' => $data['email'],
            'token' => Str::random(48),
            'status' => 'pending',
            'expires_at' => now()->addDays(3),
        ]);

        // OPTION: si l'utilisateur existe déjà, on crée un membership "pending"
        if ($existingUser) {
            $colocation->members()->syncWithoutDetaching([
                $existingUser->id => ['status' => 'pending', 'left_at' => null],
            ]);
        }

        // envoi email
        Mail::to($data['email'])->send(new ColocationInvitationMail($invitation->load('colocation')));

        return back()->with('success', 'Invitation envoyée.');
    }

    // utilisateur voit la page accept/refuse

    public function show(Request $request, string $token)
    {
        $invitation = Invitation::where('token', $token)->with('colocation.owner')->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Invitation déjà traitée.');
        }

        if ($invitation->expires_at && now()->greaterThan($invitation->expires_at)) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('dashboard')->with('error', 'Invitation expirée.');
        }

        // Si guest : décider login ou register
        if (!auth()->check()) {
            $emailExists = User::where('email', $invitation->invited_email)->exists();

            $target = $emailExists ? route('login') : route('register');

            // guest() garde l'URL intended => après login/register on revient ici
            return redirect()->guest($target)
                ->with('info', 'Connectez-vous / inscrivez-vous avec le même email que l’invitation.');
        }

        // Si connecté : vérifier que l’email correspond à l’invitation
        if ($request->user()->email !== $invitation->invited_email) {
            abort(403);
        }

        return view('invitations.show', compact('invitation'));
    }

    public function accept(Request $request, string $token)
    {
        $invitation = Invitation::where('token', $token)->with('colocation')->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Invitation déjà traitée.');
        }

        if ($invitation->expires_at && now()->greaterThan($invitation->expires_at)) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('dashboard')->with('error', 'Invitation expirée.');
        }

        // Vérifier email
        if ($request->user()->email !== $invitation->invited_email) {
            abort(403);
        }

        // Règle: une seule colocation active par user (membership accepté + pas left + coloc active)
        $hasActive = $request->user()->colocations()
            ->wherePivot('status', 'accepted')
            ->wherePivot('left_at', null)
            ->where('colocations.status', 'active')
            ->exists();

        if ($hasActive) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une colocation active.');
        }

        // accepter membership (si pending existe, update; sinon attach)
        $colocation = $invitation->colocation;

        $alreadyPivot = $colocation->members()->where('users.id', $request->user()->id)->exists();

        if ($alreadyPivot) {
            $colocation->members()->updateExistingPivot($request->user()->id, [
                'status' => 'accepted',
                'left_at' => null,
            ]);
        } else {
            $colocation->members()->attach($request->user()->id, [
                'status' => 'accepted',
                'left_at' => null,
            ]);
        }

        $invitation->update(['status' => 'accepted']);

        return redirect()->route('dashboard')->with('success', 'Invitation acceptée.');
    }

    public function refuse(Request $request, string $token)
    {
        $invitation = Invitation::where('token', $token)->with('colocation')->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Invitation déjà traitée.');
        }

        // Vérifier email
        if ($request->user()->email !== $invitation->invited_email) {
            abort(403);
        }

        $invitation->update(['status' => 'refused']);

        // si pivot pending existe, on peut le supprimer (optionnel)
        $invitation->colocation->members()->detach($request->user()->id);

        return redirect()->route('dashboard')->with('success', 'Invitation refusée.');
        
    }
}