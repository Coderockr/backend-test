<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Owner;
use App\Services\InvestmentService;
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

    public function test_creating_an_owner_investment_without_filling_out_all_fields()
    {
        Artisan::call('db:seed');
        $response = $this->post("api/investments", []);
        $messages = $response->original['message']->getMessages();

        $messages = array_map(fn ($item) =>($item[0]), $messages);

        $this->assertContains('The invested amount field is required.', $messages);
        $this->assertContains('The investment date field is required.', $messages);
        $this->assertContains('The owner id field is required.', $messages);
    }

    public function test_creating_an_owner_investment_with_all_fields_filled_in()
    {
        Artisan::call('db:seed');

        $owner = Owner::all()->first();
        $investmentDate = Carbon::now()->addDays(-10);

        $investment = [
            'investment_date' => $investmentDate->format('Y-m-d'),
            'invested_amount' => 3000.15,
            'owner_id' =>  $owner->id,
        ];

        $response = $this->post("api/investments", $investment);
        $record = $response->original->getAttributes();

        # Intact columns
        $columns = [
            'id','investment_date','invested_amount','expected_balance','status','taxes','withdrawn_amount','withdrawal_date','owner_id'
        ];

        $columnsDiff = array_diff($columns, array_keys($record));

        # Number of table columns
        $this->assertEquals(11, count($record));
        # Field names remain intact
        $this->assertEquals(0, count($columnsDiff));

        # Entered values are contained in the recording
        $this->assertContains($investment['invested_amount'], $record);
        $this->assertContains($investment['investment_date'], $record);
        $this->assertContains($investment['owner_id'], $record);
        $this->assertContains('ACTIVE', $record);
    }

    public function test_successfully_withdrawing_an_investment()
    {
        Artisan::call('db:seed');

        $owner = Owner::all()->first();
        $investmentDate = Carbon::now()->addDays(-120);
        $withdrawalDate = '2023-03-10';

        $investment = [
            'investment_date' => $investmentDate->format('Y-m-d'),
            'invested_amount' => 3000.15,
            'owner_id' =>  $owner->id,
        ];

        $response = $this->post("api/investments", $investment);

        $investmentCreated = $response->original;

        $investmentService = new InvestmentService($investmentCreated);

        $response->original->expected_balance = $investmentService->calculateGain();

        # Calculations
        $expectedBalance = $response->original->expected_balance;
        $investedAmount = $response->original->invested_amount;
        $onlyGain = round($expectedBalance - $investedAmount,2);
        $GainWithTaxDeductions = $investmentService->applyRate();
        $withdrawnAmount = round($investedAmount + $GainWithTaxDeductions,2);
        $taxes = round($onlyGain - $GainWithTaxDeductions, 2);

        # Saves previously created investment
        $response->original->save();

        # Make the withdrawal
        $withdrawalResponse = $this->post("api/investments/withdraw/{$response->original->id}", ['withdrawal_date'=> $withdrawalDate]);

        $message = ($withdrawalResponse->original['message']);
        $withdrawal = ($withdrawalResponse->original['investment']->getAttributes());

        $this->assertEquals('Full withdrawal of investment successfully!', $message);
        $this->assertEquals($withdrawalDate, $withdrawal['withdrawal_date']);
        $this->assertEquals('WITHDRAWN', $withdrawal['status']);
        $this->assertEquals($onlyGain, $withdrawal['expected_balance']);
        $this->assertEquals($withdrawnAmount, $withdrawal['withdrawn_amount']);
        $this->assertEquals($taxes, $withdrawal['taxes']);
    }
}
