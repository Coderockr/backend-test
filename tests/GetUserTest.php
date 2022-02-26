<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class GetUserTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetUser()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->call('GET', 'user');
        $this->assertEquals(200, $response->status());
    }
}
