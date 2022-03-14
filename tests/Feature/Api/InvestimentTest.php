<?php

namespace Tests\Feature\Api;

use App\Models\Investiment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvestimentTest extends TestCase
{

    public function test_list_investiments()
    {
        $response = $this->getJson('/investiments', $this->defaultHeaders);
        // $response->dump();
        $response->assertStatus(200);
    }

    public function test_create_investiment()
    {
        $investiment = Investiment::factory()->create();

        $response = $this->postJson('/investiment', [
            'user_id' => $investiment->user_id,
            'value'   => $investiment->value,
            'investiment_date' => $investiment->investiment_date
        ]);

        $response->assertStatus(201);
    }

    public function test_create_investiment_negative()
    {

        $response = $this->postJson('/investiment', [
            'user_id' => 1,
            'value'   => -1485.00,
            'investiment_date' => "2022-03-13"
        ]);

        $response->assertStatus(422);
    }

    public function test_show_investiment()
    {
        $investiment = Investiment::factory()->create();

        $response = $this->getJson('/investiment/'.$investiment->id, $this->defaultHeaders);

        $response->assertStatus(200);
    }


}
