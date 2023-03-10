<?php

use App\Models\Investment;
use App\Models\Investor;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Money\Money;
use Tests\TestCase;

class InvestmentControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private Investor $investorModel;
    private Investment $investmentModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->investorModel = $this->app->make(Investor::class);
        $this->investmentModel = $this->app->make(Investment::class);
    }

    public function testGetInvestmentsFromInvestor()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $this->investmentModel
            ->factory()
            ->createMany([
                [
                    'investor_id' => $investor->id,
                ],
                [
                    'investor_id' => $investor->id,
                ]
            ]);

        $this->get("/v1/investiment/{$investor->id}");

        $this->assertResponseOk();
        $this->assertCount(2, $this->response->original);
    }

    public function testGetAInvestmentFromInvestor()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel->factory()->createOne(['investor_id' => $investor->id]);
        $this->investmentModel
            ->factory()
            ->createMany([
                [
                    'investor_id' => $investor->id,
                ],
                [
                    'investor_id' => $investor->id,
                ]
            ]);

        $this->get("/v1/investiment/{$investor->id}/investment/{$investmentCreated->id}");

        $this->assertResponseOk();
        $this->assertInstanceOf(Investment::class, $this->response->original);
        $this->assertEquals($investor->id, $this->response->original->investor_id);
        $this->assertEquals($investmentCreated->id, $this->response->original->id);
    }

    public function testCreateInvestment()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $this->investmentModel->factory()->createOne(['investor_id' => $investor->id]);

        $this->post("/v1/investiment/{$investor->id}/create", [
            'name' => 'Poupança',
            'create_date' => \Carbon\Carbon::now()->timestamp,
            'balance' => 1000,
        ]);

        $this->assertResponseOk();
        $this->assertInstanceOf(Investment::class, $this->response->original);
        $this->seeInDatabase('investments', ['name' => 'Poupança', 'balance' => 1000]);
    }

    public function testWithdrawAInvestment()
    {
        $investor = $this->investorModel->factory(1)->createOne();
        $investmentCreated = $this->investmentModel
            ->factory()
            ->createOne([
                'investor_id' => $investor->id,
                'create_date' => Carbon\Carbon::now()->subDay(),
                'balance' => Money::BRL(1000),
                'investment_balance' => Money::BRL(200)
            ]);

        $this->post("/v1/investiment/{$investor->id}/withdraw-investment", ['investment_id' => $investmentCreated->id]);

        $this->assertInstanceOf(Investment::class, $this->response->original);
        $this->assertEquals(155, $this->response->original->investment_balance->getAmount());
        $this->assertEquals(1155, $this->response->original->balance->getAmount());
        $this->assertEquals(1200, $this->response->original->expected_balance->getAmount());
    }
}
