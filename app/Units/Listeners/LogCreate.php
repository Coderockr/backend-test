<?php

namespace App\Units\Listeners;

use App\Domains\Log\Services\LogService;
use App\Units\Events\LogEvent;

class LogCreate
{
    /**
     *
     * @var LogService
     */
    private $service;

    /**
     * Cria ouvinte de evento.
     *
     * @return void
     */
    public function __construct(LogService $service)
    {
        $this->service = $service;
    }

    /**
     * Lidar com o evento.
     *
     * @param  LogEvent  $event
     * @return void
     */
    public function handle(LogEvent $event)
    {
        $event = $event->event;
        $this->service->create($event);
    }
}
