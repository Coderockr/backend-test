<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventInvitationFactory extends Factory
{
    protected $model = EventInvitation::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'event_id' => Event::factory()->create(),
            'friend_id' => User::factory()->create(),
        ];
    }
}
