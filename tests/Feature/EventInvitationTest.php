<?php

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\User;

it('list correctly', function () {
    $user = User::factory()->create();
    EventInvitation::factory()->count(2)->create(['user_id' => $user->id]);

    $this->actingAs($user, 'api')
        ->get(route('event-invitations'))
        ->assertStatus(200)
        ->assertJson([
            ['user_id' => $user->id],
            ['user_id' => $user->id],
        ])
        ->assertJsonStructure([
            '*' => [
                'id', 'user_id', 'event_id', 'friend_id', 'user', 'event', 'friend', 'created_at', 'updated_at',
            ],
        ]);
});

it('store correctly', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();
    $friend = User::factory()->create();
    $payload = [
        'event_id' => $event->id,
        'friend_id' => $friend->id,
    ];

    $this->actingAs($user, 'api')
        ->post(route('event-invitations.store'), $payload)
        ->assertStatus(201)
        ->assertJson($payload)
        ->assertJson([
            'user_id' => $user->id,
        ]);
});
