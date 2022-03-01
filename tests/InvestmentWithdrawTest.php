<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
Use Carbon\Carbon;

class InvestmentWithdrawTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void 
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->investment = $this->user->investments()->create([ 
            "value" => 5570.58,
            "created_at" => '2012-02-20 13:50:20'
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSimpleWithdrawn()
    {
        $response = $this->actingAs($this->user)->call('PUT', 'investments/withdraw/'.$this->investment->id);
        $this->assertEquals(200, $response->status());

        $data = boolval($response->decodeResponseJson());
        $this->assertTrue($data);     
        
        $investment = $this->user->investments()->find($this->investment->id);

        $this->assertTrue($investment->withdrawn);
    }

    public function testWithFutureDate()
    {
        $response = $this->actingAs($this->user)
            ->call('PUT', 'investments/withdraw/'.$this->investment->id, [
                "date" => '2070-02-20 00:00:00'
            ]);

        //response status with validations errors
        $this->assertEquals(422, $response->status());
        $response->assertJsonValidationErrors('date', null);
    }

    public function testWithBeforeCreateDate()
    {
        $response = $this->actingAs($this->user)
        ->call('PUT', 'investments/withdraw/'.$this->investment->id, [
            "date" => '2011-02-20 00:00:00'
        ]);

        //response status with validations errors
        $this->assertEquals(422, $response->status());
        $response->assertJsonValidationErrors('date', null);
    }

    public function testWithPastDate()
    {
        $response = $this->actingAs($this->user)
        ->call('PUT', 'investments/withdraw/'.$this->investment->id, [
            "date" => '2015-02-20 00:00:00'
        ]);

        $this->assertEquals(200, $response->status());

        $data = boolval($response->decodeResponseJson());
        $this->assertTrue($data);     
        
        $investment = $this->user->investments()->find($this->investment->id);

        $this->assertTrue($investment->withdrawn);
    }
}
