<?php

use App\Models\Event;

it('list correctly', function () {
    Event::factory()->create(['name' => 'First event']);
    Event::factory()->create(['name' => 'Second event']);

    $this->get(route('home'))
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

    $this->get(route('home', ['page' => 2]))
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

it('lets filter by date', function () {
    $date = now()->addDays(2);

    Event::factory()->count(10)->create();
    Event::factory()->create(['moment' => $date->format('Y-m-d 22:15'),]);
    Event::factory()->create(['moment' => $date->format('Y-m-d 23:30'),]);

    $this->get(route('home', ['date' => $date->format('Y-m-d')]))
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                ['moment' => $date->format('Y-m-d 22:15'),],
                ['moment' => $date->format('Y-m-d 23:30'),],
            ],
        ]);
});

it('lets filter by date and time', function () {
    $date = now()->addDays(2);

    Event::factory()->count(10)->create();
    Event::factory()->count(2)->create(['moment' => $date->format('Y-m-d H:i'),]);

    $payload = [
        'date' => $date->format('Y-m-d'),
        'time' => $date->format('H:i'),
    ];

    $this->get(route('home', $payload))
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                ['moment' => $date->format('Y-m-d H:i'),],
                ['moment' => $date->format('Y-m-d H:i'),],
            ],
        ])
        ->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id', 'name', 'description', 'location', 'moment', 'created_at', 'updated_at',
                ],
            ],
        ]);
});

it('lets filter by location', function () {
    Event::factory()->count(10)->create();
    Event::factory()->create(['location' => 'Somewhere in America.',]);
    Event::factory()->create(['location' => 'Somewhere in Europe.',]);

    $this->get(route('home', ['location' => 'somewhere']))
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                ['location' => 'Somewhere in America.',],
                ['location' => 'Somewhere in Europe.',],
            ],
        ]);
});
