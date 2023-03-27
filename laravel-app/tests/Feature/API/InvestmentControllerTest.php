<?php

namespace Tests\Feature\API;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Investment;
use App\Models\Owner;
use Illuminate\Support\Facades\Artisan;

class InvestmentControllerTest extends TestCase
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

    public function test_list_all_investments()
    {
        Artisan::call('db:seed');
        $response = $this->getJson('api/investments');
        $response->assertOk();
    }

    public function test_show_investment_success()
    {
        Artisan::call('db:seed');
        $owner = Investment::all()->first()->id;
        $response = $this->getJson("api/investments/{$owner}");
        $response->assertOk();
    }

    public function test_show_investment_failure()
    {
        $investment = '98c65ba7-ff55-48b8-9fa6-54921d7c5d32';
        $response = $this->getJson("api/investments/{$investment}");
        $response->assertNotFound();
    }

    public function test_investment_withdraw_successs()
    {
        Artisan::call('db:seed');

        $investment = Investment::all()->first();
        $oneMoreDay = Carbon::createFromDate($investment->investment_date)->addDays(1);

        $data = [
            'withdrawal_date' => $oneMoreDay->format('Y-m-d')
        ];

        $response = $this->postJson("api/investments/withdraw/{$investment->id}", $data);
        $response->assertOk();
    }

    public function test_investment_withdrawal_before_investment_creation_failure()
    {
        Artisan::call('db:seed');

        $investment = Investment::all()->first();
        $dayLess = Carbon::createFromDate($investment->investment_date)->addDays(-1);

        $data = [
            'withdrawal_date' => $dayLess->format('Y-m-d')
        ];

        $response = $this->postJson("api/investments/withdraw/{$investment->id}", $data);
        $response->assertBadRequest();
    }

    public function test_investment_withdrawal_in_the_future_failure()
    {
        Artisan::call('db:seed');

        $investment = Investment::all()->first();
        $oneDayInTheFuture = Carbon::now()->addDays(1);

        $data = [
            'withdrawal_date' => $oneDayInTheFuture->format('Y-m-d')
        ];

        $response = $this->postJson("api/investments/withdraw/{$investment->id}", $data);
        $response->assertBadRequest();
    }

    public function test_destroy_investment()
    {
        Artisan::call('db:seed');
        $owner = Investment::all()->first()->id;
        $response = $this->deleteJson("api/investments/{$owner}");
        $response->assertOk();
    }

    public function test_destroy_investment_failure()
    {
        $investment = '98c65ba7-ff55-48b8-9fa6-54921d7c5d32';
        $response = $this->deleteJson("api/investments/{$investment}");
        $response->assertNotFound();
    }

    public function test_store_investment_success()
    {
        Artisan::call('db:seed');

        $owner = Owner::all()->first();
        $investmentDate = Carbon::now()->addDays(-10);

        $investment = [
            'investment_date' => $investmentDate->format('Y-m-d'),
            'invested_amount' => 3000.15,
            'owner_id' =>  $owner->id,
        ];

        $response = $this->postJson("api/investments/", $investment);
        $response->assertCreated();
    }

    public function test_store_investment_with_creation_date_in_the_future_failure()
    {
        Artisan::call('db:seed');

        $owner = Owner::all()->first();
        $investmentDate = Carbon::now()->addDays(1);

        $investment = [
            'investment_date' => $investmentDate->format('Y-m-d'),
            'invested_amount' => 3000.15,
            'owner_id' =>  $owner->id,
        ];

        $response = $this->postJson("api/investments/", $investment);
        $response->assertBadRequest();
    }

    public function tearDown(): void
    {
        Artisan::call('cache:clear');
        Artisan::call('migrate:refresh');
        parent::tearDown();
    }
}
