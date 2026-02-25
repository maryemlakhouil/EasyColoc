<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PromoteFirstUserToGlobalAdmin 
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     * le premier user inscrit devien un admin global 
     */

    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;

        // Le premier user créé dans la base
        if (User::count() === 1) {
            $user->update(['role' => 'admin']);
        }
    }
   
}


