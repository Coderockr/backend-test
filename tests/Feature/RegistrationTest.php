<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class RegistrationTest extends TestCase
{
    private $api = '/api/auth';

    /** @test */
    public function new_user_can_register()
    {
        $user = factory(User::class)->make();
        $user = array_merge($user->toArray(), ['password' => $user->password]);

        $response = $this->json('POST', $this->api . '/register', $user);
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => $user['email']
        ]);
    }

    /** @test */
    public function existing_user_cannot_register()
    {
        $existing_user = factory(User::class)->create();
        $user = array_merge($existing_user->toArray(), ['password' => $existing_user->password]);

        $response = $this->json('POST', $this->api . '/register', $user);

        $response->assertStatus(422)->assertJson([
            'status' => 422,
            'errors' => [
                'email' => ['The email has already been taken.']
            ]
        ]);
    }
}
