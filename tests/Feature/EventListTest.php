<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventListTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_event_list_are_publicly_available()
    {
        $this->get(route('events'))->assertStatus(200);
    }

    public function test_event_list_are_privately_available()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')
            ->get(route('events'))
            ->assertStatus(200);
    }

    public function test_events_are_listed_correctly()
    {
        Event::factory()->create(['name' => 'First event',]);
        Event::factory()->create(['name' => 'Second event',]);

        $this->get(route('events'))
            ->assertStatus(200)
            ->assertJson([
                'current_page' => 1,
                'data' => [
                    ['name' => 'First event'],
                    ['name' => 'Second event'],
                ],
            ]);
    }

    public function test_event_list_can_be_paginated()
    {
        Event::factory()->count(250)->create();

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
                'data' => [
                    '*' => [
                        'id', 'name', 'description', 'location', 'moment', 'created_at', 'updated_at',
                    ],
                ],
            ]);
    }

    public function test_event_list_can_be_filtered_by_date()
    {
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
    }

    public function test_event_list_can_be_filtered_by_date_and_time()
    {
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
    }

    public function test_event_list_can_be_filtered_by_location()
    {
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
    }
}
