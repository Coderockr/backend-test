<?php

namespace App\Service;

use App\Models\Investment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Money\Money;

class RateService
{
    protected const RATEINVESTMENT = 0.52;
    protected const RATEWITHDRAWUNTILONEYEAR = 0.225;
    protected const RATEWITHDRAWDEFAULT = 0.185;
    protected const RATEWITHDRAWMORETWOYEARS = 0.15;

    /**
     * @param Investment $investment
     * @return Investment
     */
    public function applyRateWithdraw(Investment $investment): Investment
    {
        $investmentTime = $investment->create_date->diffInYears(new \DateTime()) + 1;

        return match (true) {
            $investmentTime <= 1 => $this->applyRateOneYearWithdraw($investment),
            $investmentTime > 2 => $this->applyRateMoreOneYearWithdraw($investment),
            default => $this->applyRateDefaultWithdraw($investment)
        };
    }

    /**
     * @param Collection $investments
     * @param Carbon $date
     * @return \Illuminate\Support\Collection
     */
    public function applyRateInvestment(Collection $investments, Carbon $date): \Illuminate\Support\Collection
    {
        return $investments->map(function (Investment $investment) {
            $totalValue = $investment->balance->add($investment->investment_balance);

            $investment->investment_balance =
                $investment->investment_balance->add($totalValue->multiply(self::RATEINVESTMENT));

            $investment->expected_balance = Money::sum($investment->balance, $investment->investment_balance);

            $investment->rate_applied = self::RATEINVESTMENT;

            return $investment;
        });
    }

    /**
     * @param Investment $investment
     * @return Investment
     */
    private function applyRateOneYearWithdraw(Investment $investment): Investment
    {
        $newInvetmentBalance = $this->applyRate($investment, self::RATEWITHDRAWUNTILONEYEAR);

        $investment->expected_balance = Money::sum($investment->balance, $investment->investment_balance);
        $investment->actual_investment_balance = $investment->investment_balance;
        $investment->investment_balance = $newInvetmentBalance;
        $investment->rate_applied = self::RATEWITHDRAWUNTILONEYEAR;
        $investment->balance = $investment->balance->add($investment->investment_balance);
        return $investment;
    }

    /**
     * @param Investment $investment
     * @return Investment
     */
    private function applyRateDefaultWithdraw(Investment $investment): Investment
    {
        $newInvetmentBalance = $this->applyRate($investment, self::RATEWITHDRAWDEFAULT);
        $investment->expected_balance = Money::sum($investment->balance, $investment->investment_balance);

        $investment->actual_investment_balance = $investment->investment_balance;
        $investment->investment_balance = $newInvetmentBalance;
        $investment->rate_applied = self::RATEWITHDRAWDEFAULT;
        $investment->balance = $investment->balance->add($investment->investment_balance);
        return $investment;
    }

    /**
     * @param Investment $investment
     * @return Investment
     */
    private function applyRateMoreOneYearWithdraw(Investment $investment): Investment
    {
        $newInvetmentBalance = $this->applyRate($investment, self::RATEWITHDRAWMORETWOYEARS);

        $investment->actual_investment_balance = $investment->investment_balance;
        $investment->investment_balance = $newInvetmentBalance;
        $investment->rate_applied = self::RATEWITHDRAWMORETWOYEARS;
        $investment->balance = $investment->balance->add($investment->investment_balance);
        return $investment;
    }

    /**
     * @param Investment $investment
     * @param float $rate
     * @return Money
     */
    private function applyRate(Investment $investment, float $rate): Money
    {
        $currentGain = $investment->investment_balance->getAmount();
        $valueDiscount = $currentGain * $rate;
        return Money::BRL($currentGain - $valueDiscount);
    }
}
