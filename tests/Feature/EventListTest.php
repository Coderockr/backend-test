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
                'per_page' => 10,
                'data' => [
                    ['name' => 'First event'],
                    ['name' => 'Second event'],
                ],
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

    public function test_event_list_can_be_paginated()
    {
        Event::factory()->count(250)->create();

        $this->get(route('events', ['page' => 2]))
            ->assertStatus(200)
            ->assertJson([
                'current_page' => 2,
                'per_page' => 10,
            ]);
    }
}
