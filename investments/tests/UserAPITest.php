<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Faker\Factory as Faker;

class UserApiTest extends TestCase
{

    public function testApiCreateUserCorrectly()
    {
        $token = $this->JWTtoken('admin');
        $faker = Faker::create();

        $this->json(
            'POST',
            '/api/v1/user/create',
            [
                'name'     => $faker->name,
                'email'    => $faker->email,
                'password' => $faker->password,
                'role'     => (rand(0,1)? 'admin': 'customer')
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(200);

        $jsonResponse = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('data', $jsonResponse);
        $this->assertEquals('success', $jsonResponse['status']);
    }

    public function TestApiListUsers()
    {
        $token = $this->JWTtoken('admin');
        $this->json(
            'POST',
            '/api/v1/user/list',
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(200);

        $jsonResponse = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('data', $jsonResponse);

    }

    private function JWTtoken($role)
    {
        $this->post(
            '/auth/login',
            [
                'email' => $role . '@email.com',
                'password'=> 'passwd123'
            ]
        );

        $r = json_decode($this->response->getContent());

        if ($r->access_token) {
            return  $r->access_token;
        }
    }
}
