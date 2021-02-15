<?php

use App\Models\Event;
use App\Models\User;
use function Pest\Faker\faker;

it('list correctly', function () {
    $user = User::factory()->create();
    Event::factory()->create(['user_id' => $user->id, 'name' => 'First event']);
    Event::factory()->create(['user_id' => $user->id, 'name' => 'Second event']);

    $this->actingAs($user, 'api')
        ->get(route('events'))
        ->assertStatus(200)
        ->assertJson([
            ['user_id' => $user->id, 'name' => 'First event'],
            ['user_id' => $user->id, 'name' => 'Second event'],
        ])
        ->assertJsonStructure([
            '*' => [
                'id', 'user_id', 'name', 'description', 'location', 'moment', 'user', 'created_at', 'updated_at',
            ],
        ]);
});

it('store correctly', function () {
    $user = User::factory()->create();
    $payload = [
        'name' => faker()->sentence(3, true),
        'description' => faker()->text,
        'location' => faker()->streetAddress,
        'moment' => now()->addWeek()->format('Y-m-d H:i'),
    ];

    $this->actingAs($user, 'api')
        ->post(route('events.store'), $payload)
        ->assertStatus(201)
        ->assertJson($payload)
        ->assertJson([
            'user_id' => $user->id,
        ]);
});

it('update correctly', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $user->id]);
    $payload = [
        'name' => 'Updated name',
        'description' => 'Some updated description',
        'location' => 'A location updated',
        'moment' => now()->addWeek()->format('Y-m-d H:i'),
    ];

    $this->actingAs($user, 'api')
        ->put(route('events.update', $event->id), $payload)
        ->assertStatus(200)
        ->assertJson($payload)
        ->assertJson([
            'user_id' => $user->id,
        ]);
});

it('delete correctly', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user, 'api')
        ->delete(route('events.delete', $event->id))
        ->assertStatus(204);
});
