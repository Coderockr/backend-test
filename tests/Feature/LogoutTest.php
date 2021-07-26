<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    private $api = '/api/auth';

    /** @test */
    public function token_can_be_logged_out()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('password')
        ]);

        $credentials = [
            'email' => $user['email'],
            'password' => 'password'
        ];

        $response = $this->json('POST', $this->api . '/login', $credentials);
        $token = $response->getData()->access_token;

        $response = $this->json('POST', $this->api . '/logout', [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $message = $response->getData()->message;
        $this->assertEquals('Successfully logged out.', $message);
    }
}
