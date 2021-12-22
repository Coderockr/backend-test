<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Investment;
use DateTimeZone;
use DateTime;

class WithdrawalTest extends TestCase
{
    public function testRequiredFields()
    {
        $this->json('POST', 'api/withdrawal', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "Error"=> "Investment not found"
            ]);
    }

    public function testSuccessful()
    {
        $investment = Investment::factory()->create()->id;
        $dtz = new DateTimeZone("America/Fortaleza");
        $now = new DateTime("now", $dtz);

        $data = [
            "investment" => $investment,
            "date"       => $now->format("y-m-d")
        ];
        
        $this->json('POST', 'api/withdrawal', $data,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "final_value"
            ]);
    }
}
