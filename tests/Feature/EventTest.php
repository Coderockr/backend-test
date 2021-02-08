<?php

use App\Models\Event;
use App\Models\User;
use function Pest\Faker\faker;

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
        ->assertJson($payload);
});

it('update correctly', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();
    $payload = [
        'name' => 'Updated name',
        'description' => 'Some updated description',
        'location' => 'A location updated',
        'moment' => now()->addWeek()->format('Y-m-d H:i'),
    ];

    $this->actingAs($user, 'api')
        ->put(route('events.update', $event->id), $payload)
        ->assertStatus(200)
        ->assertJson($payload);
});

it('delete correctly', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();

    $this->actingAs($user, 'api')
        ->delete(route('events.delete', $event->id))
        ->assertStatus(204);
});
