<?php


namespace Investment;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Investment
{

    protected $currentAmount = 0;
    protected $gainAmount = 0;
    protected $withdrawalDates = [];
    protected $withdrawalMonths = [];
    const PROFIT = 0.0052;

    public function __construct($creationDate, $amount, $limitDate, array $withdrawn=[]) {
        $this->creationDate = $creationDate;
        $this->amount = transfomrAmountToInt($amount);
        $this->withdrawals = $withdrawn;
        $this->limitDate = $limitDate;
    }

    public function balance()
    {
        $this->profitCalc();

        return $this->currentAmount;
    }


    public function profitCalc()
    {
        $this->currentAmount = $this->amount;

        $period = new CarbonPeriod(
            Carbon::create($this->creationDate)->addDay(),
            Carbon::create($this->limitDate)
        );

        $this->parseWithdrawals();

        $this->runPeriodGainsAndWithdrawal($period, Carbon::create($this->creationDate));
    }
    
    public function runPeriodGainsAndWithdrawal($period, $initialDate)
    {
        foreach ($period as $date) {
            if ($this->shouldApplyGain($date, $initialDate)) {
                $this->applyGain();
            }
            
            if (isset($this->withdrawalDates[$date->format('Ymd')])) {
                foreach ($this->withdrawalDates[$date->format('Ymd')] as $withdrawalAmount) {
                    if (empty($this->withdrawal($withdrawalAmount))) {
                        return;
                    }
                }
           }
        }
    }

    public function shouldApplyGain($date, $initialDate)
    {
        if ($date->day == $initialDate->day) {
            $init = $date->copy()->subMonth();
            $period = new CarbonPeriod(
                Carbon::create($init),
                Carbon::create($date)
            );

            foreach ($period as $inDate) {
                if (isset($this->withdrawalDates[$inDate->format('Ymd')]) && $inDate->day != $initialDate->day) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    public function withdrawal($amount)
    {
        $sub = ($this->currentAmount - $amount);
        if ($sub <= 0) {
            $this->currentAmount = 0;
            return false;
        }

        $this->currentAmount = $sub;
        return true;
    }

    public function getGainAmount()
    {
        return $this->gainAmount;
    }

    public function getCurrentAmount()
    {
        return $this->currentAmount;
    }

    public function applyGain()
    {
        $gain = $this->currentAmount * self::PROFIT;
        $this->gainAmount += $gain;
        $this->currentAmount += $gain;
    }

    public function parseWithdrawals()
    {
        if (is_array($this->withdrawals)) {
            foreach ($this->withdrawals as $withdrawl) {
                $withdrawalDate = Carbon::create($withdrawl->withdrawalDate);
                $this->withdrawalDates [$withdrawalDate->format('Ymd')] [] = $withdrawl->amount;
            }
        }
    }

}
