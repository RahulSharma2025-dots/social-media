<?php

namespace App\Providers;

use App\Listeners\UpdateUserOnlineStatus;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Authenticated::class => [
            [UpdateUserOnlineStatus::class, 'handleAuthenticated'],
        ],
        Logout::class => [
            [UpdateUserOnlineStatus::class, 'handleLogout'],
        ],
    ];

    public function boot(): void
    {
        //
    }
} 