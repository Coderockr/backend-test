<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use DateTimeZone;
use DateTime;

class CreationTest extends TestCase
{
    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/creation', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "owner"=> [
                    "The owner field is required."
                ],
                "initial_amount"=> [
                    "The initial amount field is required."
                ],
                "creation"=> [
                    "The creation field is required."
                ]
            ]);
    }

    public function testFutureDate()
    {
        $dtz = new DateTimeZone("America/Fortaleza");
        $now = new DateTime("now", $dtz);
        $now->modify('+1 day');

        $data = [
            'owner' => rand(1, 5),
            'creation' => $now->format("Y-m-d"),
            'initial_amount' => rand(100, 10000)
        ];

        $this->json('POST', 'api/creation', $data, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "creation"=> [
                    "The creation must be a date before or equal to 2021-12-22."
                ]
            ]);
    }

    public function testSuccessfulRegistration()
    {
        $dtz = new DateTimeZone("America/Fortaleza");
        $now = new DateTime("now", $dtz);

        $data = [
            'owner' => rand(1, 5),
            'creation' => $now->format("Y-m-d"),
            'initial_amount' => rand(100, 10000)
        ];

        $this->json('POST', 'api/creation', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "status" => "Success"
            ]);
    }
}
