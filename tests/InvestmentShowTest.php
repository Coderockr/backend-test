<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class InvestmentShowTest extends TestCase
{

    protected function setUp(): void 
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->investment = $this->user->investments()->create([ 
            "value" => 2550.75,
            "created_at" => '2016-02-20 13:50:20'
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShow()
    {
        $response = $this->actingAs($this->user)->call('GET', 'investments/'.$this->investment->id);
        $this->assertEquals(200, $response->status());

        $data = $response->decodeResponseJson();

        $this->assertEquals($data['id'], $this->investment->id);
        $this->assertEquals($data['value'], $this->investment->value);
        $this->assertEquals($data['interest_income'], $this->investment->interest_income);
        $this->assertEquals($data['withdrawn_tax_percentage'], '15%'); // older than two tax
    }
}
