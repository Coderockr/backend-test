<?php

namespace App\Units\Providers;

use App\Units\Events\MessageEvent;
use App\Units\Listeners\MessageReturn;
use App\Units\Events\LogEvent;
use App\Units\Listeners\LogCreate;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MessageEvent::class => [
            MessageReturn::class,
        ],
        LogEvent::class => [
            LogCreate::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
