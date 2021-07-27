<?php

namespace App\Events;

use App\Models\RegisterRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RegisterRequestCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $registerRequest;

    /**
     * Create a new event instance.
     *
     * @param RegisterRequest $registerRequest
     */
    public function __construct(RegisterRequest $registerRequest)
    {
        $this->registerRequest = $registerRequest;
    }

    /**
     * @return RegisterRequest
     */
    public function getRegisterRequestModel()
    {
        return $this->registerRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
