<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Investment\Investment;
use Investment\Withdrawal;

class InvestmentTest extends TestCase
{


    public function testGainCalculationForInvestmentWithOneMonth()
    {

        $amount = transfomrAmountToInt(10000.00);

        $investment = new Investment(
            '2021-01-01',
            $amount,
            '2021-02-02',
        );

        $x1Gain = $amount * $investment::PROFIT;

        $investment->balance();

        $this->assertEquals(
            $x1Gain,
            $investment->getGainAmount()
        );
    }

    public function testBalanceCalculationForInvestmentWithOneMonth()
    {
        $amount = transfomrAmountToInt(10000.00);

        $investment = new Investment(
            '2021-01-01',
            $amount,
            '2021-02-02',
        );

        $x1Gain = $amount * $investment::PROFIT;

        $balance = $investment->balance();

        $this->assertEquals(
            ($amount + $x1Gain),
            $balance
        );
    }


    public function testBalanceCalculationForInvestmentWithOneWithdrawalInTheMonthAniversaryDay()
    {
        $amount = transfomrAmountToInt(10000.00);
        $withdrawalAmount = transfomrAmountToInt(100.00);

        $investment = new Investment(
            '2021-01-01',
            $amount,
            '2021-02-01',
            [new Withdrawal('2021-02-01', $withdrawalAmount)]
        );

        $x1Gain = $amount * $investment::PROFIT;

        $balance = $investment->balance();

        $this->assertEquals(
            ( ($amount + $x1Gain) - $withdrawalAmount),
            $balance
        );
    }

    /**
     * Value of $totalAfterOneYear found in a simulation in
     * this site https://www.thecalculatorsite.com/finance/calculators/compoundinterestcalculator.php
     *
     */
    public function testBalanceCalculationForOneYearOfInvestmentWithNoWithdrawals()
    {
        $amount = transfomrAmountToInt(10000.00);
        $withdrawalAmount = transfomrAmountToInt(100.00);

        $investment = new Investment(
            '2021-01-01',
            $amount,
            '2022-01-01',
        );

        $balance = $investment->balance();

        $totalAfterOneYear = 1064215;

        $this->assertEquals(
            $totalAfterOneYear,
            intval($balance)
        );
    }


    // public function testBalanceCalculationForOneYearOfInvestmentWithOneWithdrawalInSameDayOfMonthNiversary()
    public function testBalanceCalculationForOneYearOfInvestmentWithOneWithdrawal()
    {
        $amount = transfomrAmountToInt(10000.00);
        $withdrawalAmount = transfomrAmountToInt(100.00);

        $investment = new Investment(
            '2021-01-01',
            $amount,
            '2022-01-01',
            [
                new Withdrawal('2021-10-10', $withdrawalAmount)
            ]
        );

        $balance = $investment->balance();

        $this->assertEquals(
            1048606,
            intval($balance)
        );
    }


    public function TestConvertionFloatToInt()
    {
        $investment = new Investment(
            '2021-01-01',
            1000.00,
            '2021-02-02'
        );

        $this->assertEquals((1000.00 / 100), $investment->amount);
    }


    public function testBalanceCalculationForInvestmentLowerThanTwoMonthsWithOneWithdrawal()
    {
        $amount = transfomrAmountToInt(10000.00);
        $withdrawalAmount = transfomrAmountToInt(100.00);

        $investment = new Investment(
            '2021-08-01',
            $amount,
            '2021-09-10',
            [
                new Withdrawal('2021-09-09', $withdrawalAmount)
            ]
        );

        $x1Gain = $amount * $investment::PROFIT;

        $balance = $investment->balance();

        $this->assertEquals(
             (($amount + $x1Gain) - $withdrawalAmount),
            intval($balance)
        );
    }

}