<?php

namespace App\Providers;

use App\Events\InvitationCreated;
use App\Listeners\NotifyInvitedMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        InvitationCreated::class => [
            NotifyInvitedMail::class,
        ],
    ];

    public function boot()
    {
        //
    }
}
