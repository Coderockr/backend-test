<?php

namespace Tests\Feature\Api;

use App\Models\Investiment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WithdrawalTest extends TestCase
{
    public function test_create_withdrawal()
    {
        $investiment = Investiment::factory()->create();

        $response = $this->postJson('/withdrawal', [
            'user_id' => 1,
            'investiment_id' => $investiment->id,
            'withdraw_date' => "2022-03-02"
        ]);
        $response->assertStatus(201);
    }

    public function test_create_withdrawal_before_investiment()
    {
        $investiment = Investiment::factory()->create();

        $response = $this->postJson('/withdrawal', [
            'user_id' => 1,
            'investiment_id' => $investiment->id,
            'withdraw_date' => "2022-02-28"
        ]);
        $response->assertStatus(200);
    }

    public function test_create_withdrawal_after_today()
    {
        $investiment = Investiment::factory()->create();

        $response = $this->postJson('/withdrawal', [
            'user_id' => 1,
            'investiment_id' => $investiment->id,
            'withdraw_date' => "2023-03-14"
        ]);
        $response->assertStatus(200);
    }
}
