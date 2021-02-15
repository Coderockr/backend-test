<?php

use App\Models\FriendshipRequest;
use App\Models\User;

it('list correctly', function () {
    $user = User::factory()->create();
    FriendshipRequest::factory()->count(2)->create(['friend_id' => $user->id]);

    $this->actingAs($user, 'api')
        ->get(route('friendship-requests'))
        ->assertStatus(200)
        ->assertJson([
            ['friend_id' => $user->id],
            ['friend_id' => $user->id],
        ])
        ->assertJsonStructure([
            '*' => [
                'id', 'user_id', 'friend_id', 'user', 'friend', 'created_at', 'updated_at',
            ],
        ]);
});

it('store correctly', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();
    $payload = ['friend_id' => $friend->id];

    $this->actingAs($user, 'api')
        ->post(route('friendship-requests.store'), $payload)
        ->assertStatus(201)
        ->assertJson($payload)
        ->assertJson([
            'user_id' => $user->id,
        ]);
});
