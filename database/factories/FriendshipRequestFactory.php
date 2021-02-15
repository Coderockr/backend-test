<?php

namespace Database\Factories;

use App\Models\FriendshipRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendshipRequestFactory extends Factory
{
    protected $model = FriendshipRequest::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'friend_id' => User::factory()->create(),
        ];
    }
}
