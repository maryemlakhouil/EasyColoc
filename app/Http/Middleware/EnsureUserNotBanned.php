<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class EnsureUserNotBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): 
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->banned_at !== null) {
            Auth::logout();

            $request->session()->invalidate();
            // protège contre attaques CSRF
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Votre compte est banni.');
        }
        return $next($request);
    }
}


