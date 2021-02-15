<?php

namespace Database\Factories;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendshipFactory extends Factory
{
    protected $model = Friendship::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'friend_id' => User::factory()->create(),
        ];
    }
}
