<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvestmentTest extends TestCase
{

    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    //persons endpoint tests
    public function test_creation_person_request()
    {
        $response = $this->postJson('/api/v1/persons', [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'ssn' => $this->faker->randomNumber(9, true),
            'email' => $this->faker->email,
        ]);

        $response->dump();
        $response->assertStatus(201);
    }

    public function test_update_person(){
        $response = $this->patchJson('/api/v1/persons/1', [
            'ssn' => $this->faker->randomNumber(9, true),
        ]);

        $response->dump();
        $response->assertStatus('202');
    }

    public function test_view_person_request()
    {
        $response = $this->get('/api/v1/persons/1');

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
            'description' => $this->faker->text(20),
            'gain' => 0.52,
            'created_at' => $this->faker->dateTimeBetween('-15 years', 'now', 'UTC'),
            'initial_investment' => $this->faker->randomFloat(2, 1000, 30000),
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