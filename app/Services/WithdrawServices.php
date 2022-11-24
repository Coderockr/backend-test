<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Withdraw;
use Carbon\Carbon;

class WithdrawServices
{
    CONST LESS_THAN_YEAR = 0.225;
    CONST BETWEEN_ONE_AND_TWO_YEARS = 0.185;
    CONST GREATER_THAN_TWO_YEARS = 0.15;

    public function __construct(
        private Investment $investment,
        private Carbon $withdrawalDate
    )
    {
        $this->validation();
    }

    public function withdraw()
    {
        $tax = $this->getTaxOfInvestment();
        $initialValue = $this->investment->initial_value;
        $gains = $this->investment->movements->sum('real_gain');
        $taxValue = $gains * $tax;

        $this->investment->withdraw = true;
        $this->investment->final_value = ($initialValue + $gains) - $taxValue;
        $this->investment->save();

        $withdraw = Withdraw::create([
            'investment_id'    =>  $this->investment->id,
            'date'  =>  $this->withdrawalDate,
            'tax'   =>  $tax,
            'tax_value' =>  $taxValue,
        ]);

        return $withdraw;
    }

    private function validation()
    {
        if (! $this->withdrawalDate->between($this->investment->date, Carbon::now())) {
            throw new \Exception('Invalid date');
        }

        return $this;
    }

    private function getTaxOfInvestment()
    {
        $investmentDate = Carbon::parse($this->investment->date);
        $years = $investmentDate->diffInYears($this->withdrawalDate);

        if ($years > 2) {
            return self::GREATER_THAN_TWO_YEARS;
        }

        if ($years > 1 && $years < 2) {
            return self::BETWEEN_ONE_AND_TWO_YEARS;
        }

        return self::LESS_THAN_YEAR;
    }
}
