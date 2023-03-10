<?php

use App\Models\Investment;
use App\Models\Investor;
use App\Repositories\Investment\InvestmentRepositoryInterface;
use App\Service\RateService;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Money\Money;
use Tests\TestCase;

class RateServiceTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private Investor $investorModel;
    private Investment $investmentModel;
    private RateService $rateService;
    private InvestmentRepositoryInterface $investmentRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->investorModel = $this->app->make(Investor::class);
        $this->investmentModel = $this->app->make(Investment::class);
        $this->rateService = $this->app->make(RateService::class);
        $this->investmentRepository = $this->app->make(InvestmentRepositoryInterface::class);
    }

    // If it is less than one year old, the percentage will be 22.5%.
    public function testApplyRateWithdrawWithOneDay()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => \Carbon\Carbon::now()->subDay(),
                'investment_balance' => Money::BRL(200)
            ]);

        $investment = $this->rateService->applyRateWithdraw($investmentCreated);

        $this->assertEquals(155, $investment->investment_balance->getAmount());
    }

    // If it is less than one year old, the percentage will be 22.5%.
    public function testApplyRateWithdrawWithSixMonths()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => \Carbon\Carbon::now()->subMonths(6),
                'investment_balance' => Money::BRL(200)
            ]);

        $investment = $this->rateService->applyRateWithdraw($investmentCreated);

        $this->assertEquals(155, $investment->investment_balance->getAmount());
    }

    // If it is between one and two years old, the percentage will be 18.5%.
    public function testApplyRateWithdrawWithOneYear()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => \Carbon\Carbon::now()->subYear(),
                'investment_balance' => Money::BRL(200)
            ]);

        $investment = $this->rateService->applyRateWithdraw($investmentCreated);

        $this->assertEquals(163, $investment->investment_balance->getAmount());
    }

    // If it is between one and two years old, the percentage will be 18.5%.
    public function testApplyRateWithdrawWithOneYearAndSixMonth()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => \Carbon\Carbon::now()->subYear()->subMonths(6),
                'investment_balance' => Money::BRL(200)
            ]);

        $investment = $this->rateService->applyRateWithdraw($investmentCreated);

        $this->assertEquals(163, $investment->investment_balance->getAmount());
    }

    // If older than two years, the percentage will be 15%.
    public function testApplyRateWithdrawWithTwoYears()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => \Carbon\Carbon::now()->subYears(2),
                'investment_balance' => Money::BRL(200)
            ]);

        $investment = $this->rateService->applyRateWithdraw($investmentCreated);

        $this->assertEquals(170, $investment->investment_balance->getAmount());
    }

    // If older than two years, the percentage will be 15%.
    public function testApplyRateWithdrawWithMoreTwoYears()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => \Carbon\Carbon::now()->subYears(5),
                'investment_balance' => Money::BRL(200)
            ]);

        $investment = $this->rateService->applyRateWithdraw($investmentCreated);

        $this->assertEquals(170, $investment->investment_balance->getAmount());
    }

    public function testApplyRateInvestment()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createMany([
                [
                    'investor_id' => $investor->id,
                    'create_date' => \Carbon\Carbon::createFromDate(2022, 4, 28),
                    'last_applied_rate' => \Carbon\Carbon::createFromDate(2022, 4, 28)
                ],
                [
                    'investor_id' => $investor->id,
                    'create_date' => \Carbon\Carbon::createFromDate(2022, 8, 17),
                    'last_applied_rate' => \Carbon\Carbon::createFromDate(2022, 8, 17)
                ],
                [
                    'investor_id' => $investor->id,
                    'create_date' => \Carbon\Carbon::now(),
                    'last_applied_rate' => \Carbon\Carbon::now(),
                    'investment_balance' => Money::BRL(200),
                    'balance' => Money::BRL(1000)
                ]
            ]);

        $investments = $this->investmentRepository->allByDate(\Carbon\Carbon::now());
        $investments = $this->rateService->applyRateInvestment($investments, \Carbon\Carbon::now());

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $investments);
        $this->assertEquals(824, $investments->first()->investment_balance->getAmount());
        $this->assertEquals(1000, $investments->first()->balance->getAmount());
    }
}
