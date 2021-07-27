<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use Login;

    /** @test */
    public function registered_user_can_login()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('password')
        ]);

        $this->getUserToken(['email' => $user->email, 'password' => 'password']);
    }

    /** @test */
    public function unregistered_user_cannot_login()
    {
        $credentials = [
            'email' => 'unregistered@example.com',
            'password' => 'password'
        ];

        $response = $this->json('POST', $this->api_auth .'/login', $credentials);
        $response->assertStatus(401)->assertJson([
            'status' => 401,
            'errors' => [
                'Unauthorized.'
            ]
        ]);
    }
}
