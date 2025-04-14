<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Investment;

class ListTest extends TestCase
{
    public function testRequiredFields()
    {
        $this->json('GET', 'api/list', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "page"=> [
                    "The page field is required."
                ],
                "owner"=> [
                    "The owner field is required."
                ]
            ]);
    }

    public function testSuccessful()
    {
        $owner = Investment::factory()->create()->owner;

        $data = [
            "owner" => $owner,
            "page"  => 1
        ];
        
        $this->json('GET', 'api/list', $data,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    "id",
                    "created_at",
                    "updated_at",
                    "owner",
                    "creation",
                    "initial_amount"
                ]
            ]);
    }
}
