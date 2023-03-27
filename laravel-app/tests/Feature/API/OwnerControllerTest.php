<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Owner;
use Illuminate\Support\Facades\Artisan;

class OwnerControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config(['database.connections.sqlite_testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]]);

        $this->app['config']->set('database.default', 'sqlite_testing');
        Artisan::call('migrate');
    }

    public function test_list_all_owners()
    {
        Artisan::call('db:seed');
        $response = $this->getJson('api/owners');
        $response->assertOk();
    }

    public function test_show_owner_success()
    {
        Artisan::call('db:seed');
        $owner = Owner::all()->first()->id;
        $response = $this->getJson("api/owners/{$owner}");
        $response->assertOk();
    }

    public function test_show_owner_failure()
    {
        $owner = '98c65ba7-ff55-48b8-9fa6-54921d7c5d32';
        $response = $this->getJson("api/owners/{$owner}");
        $response->assertNotFound();
    }

    public function test_investment_list_only()
    {
        Artisan::call('db:seed');
        $owner = Owner::all()->first()->id;
        $response = $this->getJson("api/owners/only-investments/{$owner}");
        $response->assertOk();
    }

    public function test_destroy_owner()
    {
        Artisan::call('db:seed');
        $owner = Owner::all()->first()->id;
        $response = $this->deleteJson("api/owners/{$owner}");
        $response->assertOk();
    }

    public function test_destroy_owner_failure()
    {
        $owner = '98c65ba7-ff55-48b8-9fa6-54921d7c5d32';
        $response = $this->deleteJson("api/owners/{$owner}");
        $response->assertNotFound();
    }

    public function test_store_owner_success()
    {
        $owner = [
            'name' => 'Adam Desiato',
            'email' => 'adam.desiato@yourhonor.com'
        ];
        $response = $this->postJson("api/owners/", $owner);
        $response->assertCreated();
    }

    public function test_store_owner_existing_failure()
    {
        $owner = [
            'name' => 'Adam Desiato',
            'email' => 'adam.desiato@yourhonor.com'
        ];
        $response = $this->postJson("api/owners/", $owner);
        $response = $this->postJson("api/owners/", $owner);

        $response->assertBadRequest();
    }

    public function test_store_owner_name_failure()
    {
        $owner = [
            'name' => 'Ad',
            'email' => 'adam.desiato@yourhonor.com'
        ];
        $response = $this->postJson("api/owners/", $owner);
        $response->assertBadRequest();
    }

    public function test_store_owner_email_failure()
    {
        $owner = [
            'name' => 'Adam',
            'email' => 'adam'
        ];
        $response = $this->postJson("api/owners/", $owner);
        $response->assertBadRequest();
    }

    public function tearDown(): void
    {
        Artisan::call('cache:clear');
        Artisan::call('migrate:refresh');
        parent::tearDown();
    }
}
