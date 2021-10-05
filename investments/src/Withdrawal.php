<?php


namespace Investment;


class Withdrawal
{
    public function __construct($withdrawalDate, $amount) {
        $this->withdrawalDate = $withdrawalDate;
        $this->amount = transfomrAmountToInt($amount);
    }
}
