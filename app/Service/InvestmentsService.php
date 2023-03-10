<?php

namespace App\Service;

use App\Models\Investment;
use App\Repositories\Investment\InvestmentRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Service\Enums\InvestmentStatus;
use App\Service\Enums\OriginTransaction;
use App\Service\Enums\TypeTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Money\Money;

class InvestmentsService
{
    /**
     * @var InvestmentRepositoryInterface
     */
    private InvestmentRepositoryInterface $investmentRepository;

    /**
     * @var TransactionRepositoryInterface
     */
    private TransactionRepositoryInterface $transactionRepository;

    /**
     * @var RateService
     */
    private RateService $rateService;

    /**
     * @param InvestmentRepositoryInterface $investmentRepository
     * @param TransactionRepositoryInterface $transactionRepository
     * @param RateService $rateService
     */
    public function __construct(
        InvestmentRepositoryInterface $investmentRepository,
        TransactionRepositoryInterface $transactionRepository,
        RateService $rateService
    ) {
        $this->investmentRepository = $investmentRepository;
        $this->transactionRepository = $transactionRepository;
        $this->rateService = $rateService;
    }

    /**
     * @param string $id
     * @return Investment
     */
    public function getById(string $id): Investment
    {
        $investment = $this->investmentRepository->getById($id);
        $investment->expected_balance = Money::sum($investment->balance, $investment->investment_balance);
        return $investment;
    }

    /**
     * @param string $investor_id
     * @param array $investmentData
     * @return Investment
     */
    public function storeInvestment(string $investor_id, array $investmentData): Investment
    {
        $investmentData = array_merge(
            $investmentData,
            ['investor_id' => $investor_id,
            'status' => InvestmentStatus::ACTIVE]
        );
        $investmentData['create_date'] = Carbon::createFromTimestamp($investmentData['create_date']);
        $investmentData['last_applied_rate'] = Carbon::now();
        $investmentData['balance'] = Money::BRL($investmentData['balance']);
        $investmentData['investment_balance'] = Money::BRL(0);
        $investmentData['expected_balance'] = Money::BRL(0);

        $investment = $this->investmentRepository->store($investmentData);
        $this->transactionRepository->addTransaction($investment, [
            'from' => OriginTransaction::INVESTMENT,
            'type' => TypeTransaction::ADD,
            'actual_balance' => $investment->balance,
            'final_balance' => $investment->balance,
            'actual_investment_balance' => Money::BRL(0),
            'final_investment_balance' => Money::BRL(0),
            'rate_applied' => 0.0,
            'investment_id' => $investment->id
        ]);

        return $investment;
    }

    /**
     * @param string $investor_id
     * @return Collection
     */
    public function getInvestmentsFromInvestor(string $investor_id): Collection
    {
        return $this->investmentRepository->getInvestmentsFromInvestor($investor_id);
    }

    /**
     * @param string $investor_id
     * @param string $id
     * @return Investment
     */
    public function getInvestmentFromInvestor(string $investor_id, string $id): Investment
    {
        $investment = $this->investmentRepository->getInvestmentFromInvestor($investor_id, $id);
        $investment->expected_balance = Money::sum($investment->balance, $investment->investment_balance);
        return $investment;
    }

    /**
     * @param string $investor_id
     * @param string $id
     * @return Investment
     */
    public function withdrawInvestment(string $investor_id, string $id): Investment
    {
        $investment = $this->getInvestmentFromInvestor($investor_id, $id);
        $investment = $this->rateService->applyRateWithdraw($investment);

        $this->transactionRepository->addTransaction($investment, [
            'from' => OriginTransaction::INVESTMENT,
            'type' => TypeTransaction::WITHDRAWN,
            'actual_balance' => $investment->expected_balance,
            'final_balance' => $investment->balance,
            'actual_investment_balance' => $investment->actual_investment_balance,
            'final_investment_balance' => $investment->investment_balance,
            'rate_applied' => $investment->rate_applied,
            'investment_id' => $investment->id
        ]);

        $this->investmentRepository->update([
            'status' => InvestmentStatus::WITHDRAWN,
            'balance' => $investment->balance->getAmount()
        ], $investment->id);

        return $investment;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function applyRateToInvestment()
    {
        try {
            $investments = $this->investmentRepository->allByDate(Carbon::now()->subMonth());

            $investmentsUpdated = $this->rateService->applyRateInvestment($investments, Carbon::now());

            $investmentsUpdated->each(function (Investment $investment) {
//                dd("aaaa: ", $investment);

                $this->investmentRepository->update([
                    'last_applied_rate' => Carbon::now(),
                    'expected_balance' => $investment->expected_balance->getAmount(),
                    'investment_balance' => $investment->investment_balance->getAmount()
                ], $investment->id);

//                dd("aaaa: ", $investment);


                $this->transactionRepository->addTransaction($investment, [
                    'from' => OriginTransaction::INVESTMENT,
                    'type' => TypeTransaction::WITHDRAWN,
                    'actual_balance' => $investment->expected_balance,
                    'final_balance' => $investment->balance,
                    'actual_investment_balance' => $investment->balance,
                    'final_investment_balance' => $investment->expected_balance,
                    'rate_applied' => $investment->rate_applied,
                    'investment_id' => $investment->id
                ]);
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
