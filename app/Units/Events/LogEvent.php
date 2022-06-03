<?php

namespace App\Units\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     *
     * @var array
     */
    public $event;

    /**
     * Cria uma nova instância de evento.
     * 
     * @param array $event
     * @return void
     */
    public function __construct(array $event)
    {
        $this->event = $event;
    }

}
