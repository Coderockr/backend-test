<?php

namespace Tests\Feature;

use App\Jobs\SendWithdrawnalProof;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class InvestmentTest extends TestCase
{

    use WithFaker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->makeFaker('en_US');
        parent::__construct($name, $data, $dataName);
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
            'ssn' => $this->faker->randomNumber(9),
            'email' => $this->faker->email,
        ]);

        $response->dump();
        $response->assertStatus(201);
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
            'description' => $this->faker->text(30),
            'gain' => 0.52,
            'created_at' => $this->faker->dateTimeBetween( '2015-01-01 00:00:00', '2020-12-31 23:59:59')
                ->format('Y-m-d H:i:s'),
            'initial_investment' => 13700.00,
        ]);

        $response->dump();
        $response->assertStatus(201);
    }

    public function test_update_investment()
    {
        $response = $this->patchJson('/api/v1/investments/1', [
            'description' => 'Default Investment - Updated',
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
        $response = $this->getJson('/api/v1/investments/1');

        $response->dump();
        $response->assertStatus(200);
    }

    // persons investment endpoint test
    public function test_persons_investments()
    {
        $response = $this->getJson('api/v1/persons/1/investments');

        $response->dump();
        $response->assertStatus(200);
    }

    // investment withdrawn endpoint test
    public function test_investment_withdrawn()
    {
        Queue::fake([
            SendWithdrawnalProof::class,
        ]);

        $response = $this->patchJson('/api/v1/investments/1/withdrawn', [
            "is_withdrawn" => 1,
            "withdrawn_at" => $this->faker->dateTimeBetween( '2021-01-01 00:00:00', '2022-11-22 00:00:00')
                ->format('Y-m-d H:i:s'),
        ]);

        $response->dump();
        $response->assertStatus(202);
    }

}