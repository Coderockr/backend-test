<?php


namespace Investment;


class Withdrawal
{
    public function __construct($withdrawalDate, $amount) {
        $this->withdralDate = $withdrawalDate;
        $this->amount = $amount;
    }
}
