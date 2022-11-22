<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvestmentTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */

    //persons endpoint tests
    public function test_creation_person_request()
    {
        $response = $this->postJson('/api/v1/persons', [
            'first_name' => 'Daniel',
            'last_name' => 'Soares',
            'ssn' => '654984215',
            'email' => 'danielcarlossoares@gmail.com',
        ]);

        $response->dump();
        $response->assertStatus(201);
    }

    public function test_update_person_request(){
        $response = $this->patchJson('/api/v1/persons/1', [
            'ssn' => '210931223',
        ]);

        $response->dump();
        $response->assertStatus('202');
    }

    public function test_view_person_request()
    {
        $response = $this->getJson('/api/v1/persons/1');

        $response->dump();
        $response->assertStatus(200);
    }

    public function test_list_all_persons()
    {
        $response = $this->get('api/v1/persons');

        $response->dump();
        $response->assertStatus(200);
    }

    //investments endpoint tests
    public function test_creation_investment()
    {
        $response = $this->postJson('/api/v1/investments', [
            'person_id' => 1,
            'description' => 'Default Investment',
            'gain' => 0.52,
            'created_at' => '2021-08-10 09:23:00',
            'initial_investment' => 13700.00,
        ]);

        $response->dump();
        $response->assertStatus(201);
    }

    public function test_update_investment()
    {
        $response = $this->patchJson('/api/v1/investments', [
            'description' => $this->faker->text(20),
        ]);

        $response->dump();
        $response->assertStatus(202);
    }

    public function test_all_investments()
    {
        $response = $this->get('api/v1/investments');

        $response->dump();
        $response->assertStatus(200);
    }

    public function test_view_investment()
    {
        $response = $this->get('/api/v1/investments/1');

        $response->dump();
        $response->assertStatus(200);
    }

    // persons investment endpoint test
    public function test_persons_investments()
    {
        $response = $this->get('api/v1/persons/1/investments');

        $response->dump();
        $response->assertStatus(200);
    }

    // investment withdrawn endpoint test
    public function test_investment_withdrawn()
    {
        $response = $this->patchJson('/api/v1/investments/1/withdrawn', [
            "is_withdrawn" => 1,
            "withdrawn_at" => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $response->dump();
        $response->assertStatus(202);
    }

}