<?php

use App\Models\Investment;
use App\Models\Investor;
use App\Repositories\Investment\InvestmentRepositoryInterface;
use App\Service\InvestmentsService;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Money\Money;
use Tests\TestCase;

class InvestmentServiceTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private Investor $investorModel;
    private Investment $investmentModel;
    private InvestmentsService $investmentService;

    private InvestmentRepositoryInterface $investmentRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->investorModel = $this->app->make(Investor::class);
        $this->investmentModel = $this->app->make(Investment::class);
        $this->investmentService = $this->app->make(InvestmentsService::class);
        $this->investmentRepository = $this->app->make(InvestmentRepositoryInterface::class);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateAInvestmentWithValidArgs()
    {
        $investor = $this->investorModel->factory()->createOne();
        $investment = $this->investmentService->storeInvestment($investor->id, [
            'name' => 'test-investment',
            'status' => 'ACTIVE',
            'create_date' => \Carbon\Carbon::now()->timestamp,
            'last_applied_rate' => \Carbon\Carbon::now()->timestamp,
            'balance' => 500,
            'investor_id' => $investor->id
        ]);

        $this->assertInstanceOf(Investment::class, $investment);
        $this->assertInstanceOf(Collection::class, $investment->transactions);
        $this->assertEquals(1, $investment->transactions()->count());
        $this->assertEquals(500, $investment->balance->getAmount());
        $this->assertEquals(0, $investment->investment_balance->getAmount());
        $this->assertEquals($investor->id, $investment->investor_id);
    }

    public function testGetAInvestmentById()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel->factory()->createOne(['investor_id' => $investor->id]);

        $investment = $this->investmentService->getById($investmentCreated->id);

        $this->assertInstanceOf(Investment::class, $investment);
        $this->assertEquals($investmentCreated->balance->getAmount(), $investment->balance->getAmount());
    }

    public function testWithdrawAInvestmentWithUntilOneYear()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => Carbon\Carbon::now()->subDay(),
                'investment_balance' => Money::BRL(200)
            ]);

        $investment = $this->investmentService->withdrawInvestment($investor->id, $investmentCreated->id);


        $this->assertInstanceOf(Investment::class, $investment);
        $this->assertInstanceOf(Money::class, $investment->investment_balance);
        $this->assertEquals(155, $investment->investment_balance->getAmount());
    }

    public function testWithdrawAInvestmentBetweenOneAndTwoYears()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => Carbon\Carbon::now()->subMonths(18),
                'investment_balance' => Money::BRL(200)
            ]);

        $investment = $this->investmentService->withdrawInvestment($investor->id, $investmentCreated->id);

        $this->assertInstanceOf(Investment::class, $investment);
        $this->assertInstanceOf(Money::class, $investment->investment_balance);
        $this->assertEquals(163, $investment->investment_balance->getAmount());
    }

    public function testWithdrawAInvestmentWithMoreTwoYears()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => Carbon\Carbon::now()->subYears(3),
                'investment_balance' => Money::BRL(200)
            ]);

        $investment = $this->investmentService->withdrawInvestment($investor->id, $investmentCreated->id);


        $this->assertInstanceOf(Investment::class, $investment);
        $this->assertInstanceOf(Money::class, $investment->investment_balance);
        $this->assertEquals(170, $investment->investment_balance->getAmount());
    }
    public function testWithdrawCreateTransaction()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => Carbon\Carbon::now()->subDay(),
                'balance' =>  Money::BRL(1000),
                'investment_balance' => Money::BRL(200)
            ]);

        $this->investmentService->withdrawInvestment($investor->id, $investmentCreated->id);

        $this->seeInDatabase('transactions', [
            'investment_id' => $investmentCreated->id,
            'final_investment_balance' => 155,
            "actual_balance" => 1200,
            "final_balance" => 1155,
            "actual_investment_balance" => 200
        ]);
    }

    public function testStoreInvestmentCreateTransaction()
    {
        $investor = $this->investorModel->factory()->createOne();

        $investmentCreated = $this->investmentService->storeInvestment($investor->id, [
            'name' => 'test-investment',
            'status' => 'ACTIVE',
            'create_date' => \Carbon\Carbon::now()->timestamp,
            'last_applied_rate' => \Carbon\Carbon::now()->timestamp,
            'balance' => 500,
            'investor_id' => $investor->id
        ]);

        $this->seeInDatabase('transactions', [
            'investment_id' => $investmentCreated->id,
            "actual_balance" => 500,
            "final_balance" => 500,
            "actual_investment_balance" => 0,
            'final_investment_balance' => 0
        ]);
    }

    public function testApplyRateToInvestment()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $this->investmentModel
            ->factory()
            ->createMany([
                [
                    'investor_id' => $investor->id,
                    'create_date' => \Carbon\Carbon::createFromDate(2022, 4, 28)
                ],
                [
                    'investor_id' => $investor->id,
                    'create_date' => \Carbon\Carbon::createFromDate(2022, 8, 17)
                ]
            ]);

        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => \Carbon\Carbon::now(),
                'last_applied_rate' => \Carbon\Carbon::now()->subMonth(),
                'investment_balance' => Money::BRL(200),
                'balance' => Money::BRL(1000)
            ]);

//        dd($investmentCreated);

        $this->investmentService->applyRateToInvestment();

//        $investment = $this->investmentService->getById($investmentCreated->id);
//        dd($investment);


        $this->seeInDatabase('investments', ['id' => $investmentCreated->id, 'investment_balance' => 824]);
    }
}
