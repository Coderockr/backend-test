<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_events_are_created_correctly()
    {
        $user = User::factory()->create();
        $payload = [
            'name' => $this->faker->sentence(3, true),
            'description' => $this->faker->text,
            'location' => $this->faker->streetAddress,
            'moment' => $this->faker->dateTimeBetween('+1 week', '+2 week')->format('Y-m-d H:i:s'),
        ];

        $this->actingAs($user, 'api')
            ->post(route('events.store'), $payload)
            ->assertStatus(201)
            ->assertJson($payload)
            ->assertJsonStructure([
                'id', 'name', 'description', 'location', 'moment', 'created_at', 'updated_at',
            ]);
    }

    public function test_events_are_updated_correctly()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $payload = [
            'name' => 'Updated name',
            'description' => 'Some updated description',
            'location' => 'A location updated',
            'moment' => $this->faker->dateTimeBetween('+1 week', '+2 week')->format('Y-m-d H:i:s'),
        ];

        $this->actingAs($user, 'api')
            ->put(route('events.update', $event->id), $payload)
            ->assertStatus(200)
            ->assertJson($payload)
            ->assertJsonStructure([
                'id', 'name', 'description', 'location', 'moment', 'created_at', 'updated_at',
            ]);
    }

    public function test_events_are_deleted_correctly()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $this->actingAs($user, 'api')
            ->delete(route('events.delete', $event->id))
            ->assertStatus(204);
    }
}
