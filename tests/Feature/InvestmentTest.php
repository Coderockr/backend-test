<?php

namespace Tests\Feature;

use App\Models\Investment;
use App\Models\Person;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvestmentTest extends TestCase
{
    use WithFaker;

    public function test_add_new_person()
    {
        $data = [
            'first_name'    =>  $this->faker->firstName,
            'last_name'    =>  $this->faker->lastName,
            'username'    =>  $this->faker->unique()->userName,
            'email'     =>  $this->faker->unique()->email
        ];

        $response = $this->post('/api/v1/person', $data);

        $this->assertDatabaseHas('person', $data);

        $response
            ->assertJsonStructure([
                'data'  =>  [
                    'id', 'first_name', 'last_name', 'username', 'email'
                ]
            ])
            ->assertStatus(201);
    }

    public function test_show_person_details()
    {
        $response = $this->get('/api/v1/person/1');

        $response
            ->assertJsonStructure([
                'data'  =>  [
                    'id', 'first_name', 'last_name', 'username', 'email'
                ]
            ])
            ->assertStatus(200);
    }

    public function test_create_an_investment()
    {
        $response = $this->post('/api/v1/investment', [
            'initial_value' =>  '4000',
            'person_id' =>  1,
            'date'  =>  '2020-02-01'
        ]);

        $response->assertJsonPath('data.withdraw', 0)
            ->assertJsonPath('data.final_value', null)
            ->assertStatus(200);
    }

    public function test_show_investments_info()
    {
        $response = $this->get('/api/v1/investment/1/');

        $response->assertJsonStructure([
            'data'  =>  [
                'initial_value', 'date', 'withdraw', 'final_value'
            ]
        ])->assertStatus(200);
    }

    public function test_create_investment_without_person_id()
    {
        $response = $this->post('/api/v1/investment', [
            'initial_value' =>  '4000',
            'date'  =>  '2020-02-01'
        ]);

        $response->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Validation errors');
    }

    public function test_create_investment_success()
    {
        $response = $this->post('/api/v1/investment', [
            'person_id' => 1,
            'initial_value' =>  4000,
            'date'  =>  '2020-02-01'
        ]);

        $response
            ->assertJsonPath('data.date', '2020-02-01')
            ->assertJsonPath('data.withdraw', 0)
            ->assertJsonPath('data.final_value', null)
            ->assertStatus(200);
    }

    public function test_withdraw_the_investment()
    {
        $data = [
            'person_id' =>  1,
            'initial_value' =>  1000,
            'date'  =>  '2021-03-14'
        ];

        $investment = Investment::create($data);
        $this->assertDatabaseHas('investment', $data);

        $withdrawUri = sprintf('/api/v1/investment/%s/withdraw', $investment->id);

        $response = $this->post($withdrawUri, [
            'date'  =>  '2022-11-24'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.withdraw', true);
    }

    public function test_withdraw_with_inexistent_investment()
    {
        $response = $this->post('/api/v1/investment/dfas/withdraw', [
            'date'  =>  '2022-11-24'
        ]);

        $response->assertStatus(404)
            ->assertJsonPath('data.success', false);
    }

    public function test_withdraw_with_date_before_investment()
    {
        $data = [
            'person_id' =>  1,
            'initial_value' =>  1000,
            'date'  =>  '2022-03-01'
        ];

        $investment = Investment::create($data);
        $this->assertDatabaseHas('investment', $data);

        $withdrawUri = sprintf('/api/v1/investment/%s/withdraw', $investment->id);

        $response = $this->post($withdrawUri, [
            'date'  =>  '2022-01-01'
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('data.success', false);
    }
}
