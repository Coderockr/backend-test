<?php

namespace Tests\Feature;

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

    // test for function to create new investments
    public function test_creation_investment_request()
    {
        $response = $this->postJson('/api/investment', ['owner' => (int) '1', 'create_date' => '2022-03-10', 'amount' => (float) '120']);
        
        $response->dump();
        $response->assertStatus(201);
    }

    // test for function to visualize investments by id
    public function test_view_investment_request()
    {
        $response = $this->get('/api/investment/1');
 
        $response->dump();
        $response->assertStatus(200);
    }

    // test for function to view withdrawal investment
    public function test_view_withdrawal_request()
    {
        $response = $this->get('/api/withdrawal/1');
 
        $response->dump();
        $response->assertStatus(200);
    }

    // test for function to list person's investments
    public function test_list_investments_by_person()
    {
        $response = $this->get('api/list/investments/1');
 
        $response->dump();
        $response->assertStatus(200);
    }

    // test for function to list person's investments
    public function test_list_all_investments()
    {
        $response = $this->get('api/investments');
 
        $response->dump();
        $response->assertStatus(200);
    }
}
