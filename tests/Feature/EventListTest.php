<?php

use App\Models\Event;
use App\Models\User;

it('is publicly available', function () {
    $this->get(route('events'))->assertStatus(200);
});

it('is privately available', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'api')
        ->get(route('events'))
        ->assertStatus(200);
});

it('list correctly', function () {
    Event::factory()->create(['name' => 'First event']);
    Event::factory()->create(['name' => 'Second event']);

    $this->get(route('events'))
        ->assertStatus(200)
        ->assertJson([
            'current_page' => 1,
            'per_page' => 10,
            'data' => [
                ['name' => 'First event'],
                ['name' => 'Second event'],
            ],
        ])
        ->assertJsonStructure([
            'current_page',
            'per_page',
            'data' => [
                '*' => [
                    'id', 'user_id', 'name', 'description', 'location', 'moment', 'user', 'created_at', 'updated_at',
                ],
            ],
        ]);
});

it('paginate list correctly', function () {
    Event::factory()->count(100)->create();

    $this->get(route('events', ['page' => 2]))
        ->assertStatus(200)
        ->assertJson([
            'current_page' => 2,
            'per_page' => 10,
        ])
        ->assertJsonStructure([
            'current_page',
            'per_page',
            'last_page_url',
            'next_page_url',
            'data',
        ]);
});
