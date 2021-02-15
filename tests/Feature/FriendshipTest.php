<?php

use App\Models\Friendship;
use App\Models\User;

it('list correctly', function () {
    $user = User::factory()->create();
    Friendship::factory()->count(2)->create(['user_id' => $user->id]);

    $this->actingAs($user, 'api')
        ->get(route('friendships'))
        ->assertStatus(200)
        ->assertJson([
            ['user_id' => $user->id],
            ['user_id' => $user->id],
        ])
        ->assertJsonStructure([
            '*' => [
                'id', 'user_id', 'friend_id', 'user', 'friend', 'created_at', 'updated_at',
            ],
        ]);
});

it('delete correctly', function () {
    $user = User::factory()->create();
    $friendship = Friendship::factory(['user_id' => $user->id])->create();

    $this->actingAs($user, 'api')
        ->delete(route('friendships.delete', $friendship->id))
        ->assertStatus(204);
});
