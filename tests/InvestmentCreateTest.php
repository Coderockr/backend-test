<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\User\Investment;
use Carbon\Carbon;

class InvestmentCreateTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void 
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->futureDate = Carbon::createFromFormat('Y-m-d H:i:s', '2070-01-23 11:53:20');
    }

    public function testCreate()
    {
        $response = $this->actingAs($this->user)->call('POST', '/investments', [
            "value" => 150.00,
        ]);
        
        //request ok
        $this->assertEquals(200, $response->status());
    }

    public function testCreateWithFutureDate()
    {
        $response = $this->actingAs($this->user)->call('POST', '/investments', [
            "value" => 150.00,
            "created_at" => $this->futureDate
        ]);
        
        //response status with validations errors
        $this->assertEquals(422, $response->status());
        $response->assertJsonValidationErrors('created_at', null);
    }

    public function testCreateWithNegativeValue()
    {
        $response = $this->actingAs($this->user)->call('POST', '/investments', [
            "value" => -2,
        ]);

        //response status with validations errors
        $this->assertEquals(422, $response->status());
        $response->assertJsonValidationErrors('value', null);
    }
}
