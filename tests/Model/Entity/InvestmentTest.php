<?php

use App\Model\Entity\Investment as TestInvestment;

use PHPUnit\Framework\TestCase;

class InvestmentTest extends TestCase
{
    public function testCalculationOfExpectedGainsWithADefaultOneMonthPeriod()
    {
        // prepare
        $currentAmount = 1000;

        // act
        $expectedAmount = TestInvestment::calcExpectedAmount($currentAmount);

        // assert
        $this->assertEquals(
            $expectedAmount,
            1005.20
        );
    }


    public function testCalculationOfExpectedGainsWithThreeMonthPeriod()
    {
        // prepare
        $currentAmount = 1000;
        $interval = 3;

        // act
        $expectedAmount = TestInvestment::calcExpectedAmount($currentAmount, $interval);

        // assert
        $this->assertEquals(
            $expectedAmount,
            1015.60
        );
    }
}