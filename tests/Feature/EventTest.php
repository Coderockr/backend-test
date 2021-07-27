<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class EventTest extends TestCase
{
    use Login;

    private $api_events = '/api/my-events';

    /** @test */
    public function unregistered_user_cannot_store_event()
    {
        $event = factory(Event::class)->make()->toArray();

        $endpoint = $this->api_events . '/store';

        $response = $this->json('POST', $endpoint, $event);
        $response->assertStatus(401);
    }

    /** @test */
    public function registered_user_can_store_event()
    {
        $user = $this->getUser();
        $event = factory(Event::class)->make();
        $event = array_merge($event->toArray(), ['owner_id' => $user->id]);

        $token = $this->getUserToken(['email' => $user->email, 'password' => 'password']);

        $endpoint = $this->api_events . '/store';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', $endpoint, $event);

        $response->assertStatus(201);

        $this->assertDatabaseHas('events', [
            'id' => $response->getData()->id,
        ]);
    }

    /** @test */
    public function user_can_cancel_their_event()
    {
        $user = $this->getUser();
        $event = factory(Event::class)->create(['owner_id' => $user->id]);

        $token = $this->getUserToken(['email' => $user->email, 'password' => 'password']);

        $endpoint = $this->api_events . '/' . $event->id . '/cancel';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', $endpoint);

        $response->assertStatus(200)->assertJson([
            'status' => 200,
            'message' => 'Event canceled.'
        ]);
    }

    /** @test */
    public function user_cannot_cancel_different_user_event()
    {
        $user = $this->getUser();
        $owner = $this->getUser();
        $event = factory(Event::class)->create(['owner_id' => $owner->id]);

        $token = $this->getUserToken(['email' => $user->email, 'password' => 'password']);

        $endpoint = $this->api_events . '/' . $event->id . '/cancel';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', $endpoint);

        $response->assertStatus(401)->assertJson([
            'status' => 401,
            'errors' => [
                'Unauthorized.'
            ]
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
        ]);
    }
}
