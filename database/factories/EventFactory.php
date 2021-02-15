<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'name' => $this->faker->sentence(3, true),
            'description' => $this->faker->text,
            'location' => $this->faker->streetAddress,
            'moment' => $this->faker->dateTimeBetween('+1 week', '+2 week')->format('Y-m-d H:i'),
        ];
    }
}
