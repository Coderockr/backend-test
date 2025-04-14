<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Investment;

class ViewTest extends TestCase
{
    // use RefreshDatabase;

    public function testRequiredFields()
    {
        $this->json('GET', 'api/view', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "investment" => [
                    "The investment field is required."
                ]
            ]);
    }

    public function testSuccessful()
    {
        $investment = Investment::factory()->create()->id;

        $data = [
            "investment" => $investment
        ];
        
        $this->json('GET', 'api/view', $data,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "initial_amount",
                "expected_balance"
            ]);
    }
}
