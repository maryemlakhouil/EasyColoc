<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Depence;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    public function dashboard()
    {
        $totalUsers = User::count();
        $totalColocations = Colocation::count();
        $totalDepences = Depence::sum('amount');

        //  bannis
        $bannedUsers = User::where('is_banned', true)
            ->orderByDesc('id')
            ->get();

        // seulement les NON bannis dans "Utilisateurs"
        $users = User::where('is_banned', false)
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.dashboard', compact('totalUsers','totalColocations','totalDepences','bannedUsers','users'));
    }

    public function ban(User $user)
    {
        $user->update(['is_banned' => true]);
        return back()->with('success', 'Utilisateur banni.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);
        return back()->with('success', 'Utilisateur débanni.');
    }
}