<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Session;

class UpdateUserOnlineStatus
{
    /**
     * Handle the login event.
     */
    public function handleAuthenticated(Authenticated $event)
    {
        $user = $event->user;
        $user->is_online = true;
        $user->save();
    }

    /**
     * Handle the logout event.
     */
    public function handleLogout(Logout $event)
    {
        $user = $event->user;
        if ($user) {
            $user->is_online = false;
            $user->save();
        }
    }
}