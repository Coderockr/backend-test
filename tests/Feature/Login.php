<?php

namespace Tests\Feature;

use App\Models\User;

trait Login
{
    private $api_auth = '/api/auth';

    /**
     * Return an user instance
     *
     * @return mixed
     */
    public function getUser()
    {
        return factory(User::class)->create(['password' => bcrypt('password')]);
    }

    /**
     * Get a user token to set in Authorization
     *
     * @param $credentials
     * @return mixed
     */
    public function getUserToken($credentials)
    {
        $response = $this->json('POST', $this->api_auth .'/login', $credentials);
        $response->assertStatus(200);
        $token = $response->getData()->access_token;
        $this->assertNotNull($token);

        return $token;
    }
}