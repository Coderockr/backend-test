<?php


namespace Investment;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Investiment
{

    protected $currentAmount = 0;
    protected $gainAmount = 0;

    public function __construct($creationDate, $amount, $limitDate, $withdrawn=[]) {
        $this->creationDate = $creationDate;
        $this->amount = $amount;
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
        $period = new CarbonPeriod($this->creationDate, $this->limitDate);
        $initialDate = new Carbon($this->creationDate);


        foreach ($period as $date) {
            if ($date->day == $initialDate->day && ($date->month > $initialDate->month  || ($date->month == $initialDate->month && $date->year > $initialDate->year)) ) {

                foreach ($this->withdrawals as $withdrawl) {
                    $withdrawal = new Carbon($withdrawl->withdrawalDate);
                    $init = Carbon::create($date)->subMonth();
                    $inTheLastMonthHasWithdrawals = $withdrawal->between($init, $date);

                    if ($inTheLastMonthHasWithdrawals) {
                        continue;
                    }
                }

                $this->applyGain();

            }
        }

    }

    public function applyGain()
    {
        $gain = $this->currentAmount * 0.52;
        $this->gainAmount = $this->gainAmount + $gain;
        $this->currentAmount = $this->currentAmount + $gain;
        echo $this->currentAmount;
        echo '<br>';
    }
}
