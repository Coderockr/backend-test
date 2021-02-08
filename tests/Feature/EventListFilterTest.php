<?php

use App\Models\Event;

it('allow filter by date', function () {
    $date = now()->addDays(2);

    Event::factory()->count(10)->create();
    Event::factory()->create(['moment' => $date->format('Y-m-d 22:15'),]);
    Event::factory()->create(['moment' => $date->format('Y-m-d 23:30'),]);

    $this->get(route('events', ['date' => $date->format('Y-m-d')]))
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                ['moment' => $date->format('Y-m-d 22:15'),],
                ['moment' => $date->format('Y-m-d 23:30'),],
            ],
        ]);
});

it('allow filter by date and time', function () {
    $date = now()->addDays(2);

    Event::factory()->count(10)->create();
    Event::factory()->count(2)->create(['moment' => $date->format('Y-m-d H:i'),]);

    $payload = [
        'date' => $date->format('Y-m-d'),
        'time' => $date->format('H:i'),
    ];

    $this->get(route('events', $payload))
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

it('allow filter by location', function () {
    Event::factory()->count(10)->create();
    Event::factory()->create(['location' => 'Somewhere in America.',]);
    Event::factory()->create(['location' => 'Somewhere in Europe.',]);

    $this->get(route('events', ['location' => 'somewhere']))
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                ['location' => 'Somewhere in America.',],
                ['location' => 'Somewhere in Europe.',],
            ],
        ]);
});
