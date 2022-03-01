<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\User\Investment;

class InvestmentIndexTest extends TestCase
{

    use DatabaseMigrations;

    protected function setUp(): void 
    {
        parent::setUp();
        $this->investmentsLength = 30;
        $this->user = User::factory()->create();
        $this->investments = $this->user->investments()
            ->saveMany( Investment::factory($this->investmentsLength)->make() );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSimpleIndex()
    {
        $response = $this->actingAs($this->user)->call('GET', 'investments/');
        $this->assertEquals(200, $response->status());

        $response = $response->decodeResponseJson();
        $data = $response['data'];
        $totalRecords = $response['total'];
        $perPage = $response['per_page'];

        $this->assertTrue($totalRecords == $this->investmentsLength);
        $this->assertTrue($perPage == 15);
    }

    public function testPaginationPerPageParam()
    {
        $perPageParam = 3;
        $response = $this->actingAs($this->user)->call('GET', 'investments?perPage='.$perPageParam);
        $this->assertEquals(200, $response->status());

        $response = $response->decodeResponseJson();
        $perPage = $response['per_page'];
        $lastPage = $response['last_page'];

        $this->assertEquals($perPage, $perPageParam);
        $this->assertEquals($lastPage, ($this->investmentsLength / $perPageParam));
    }
}
