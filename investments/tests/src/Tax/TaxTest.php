<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Investment\Tax\Tax;

class TaxTest extends TestCase
{


    public function testTaxForInvestmentsLowerThanOneYear()
    {
        $tax = new Tax();

        $taxation = $tax->taxPercentage(
            '2021-01-01',
            '2021-02-01'
        );

        $this->assertEquals(0.225, $taxation);
    }

    public function testTaxForInvestmentsBetweenOneAndTwoYears()
    {
        $tax = new Tax();

        $taxation = $tax->taxPercentage(
            '2021-01-01',
            '2022-03-01'
        );

        $this->assertEquals(0.185, $taxation);
    }


    public function testTaxForInvestmentsGreaterThanTwoYears()
    {
        $tax = new Tax();

        $taxation = $tax->taxPercentage(
            '2021-01-01',
            '2023-03-01'
        );

        $this->assertEquals(0.15, $taxation);
    }



    public function testTaxForInvestmentsExactlyOneYear()
    {
        $tax = new Tax();

        $taxation = $tax->taxPercentage(
            '2021-01-01',
            '2022-01-01'
        );

        $this->assertEquals(0.185, $taxation);
    }

    public function testTaxForInvestmentsExactlyTwoYear()
    {
        $tax = new Tax();

        $taxation = $tax->taxPercentage(
            '2021-01-01',
            '2023-01-01'
        );

        $this->assertEquals(0.15, $taxation);
    }


    public function testIfTaxThrowsExceptionWhenInitialDateIsGreaterThanFinalDate()
    {
        $this->expectException(\Exception::class);

        $tax = new Tax();

        $tax->taxPercentage(
            '2023-01-01',
            '2021-01-01'
        );
    }


    public function testIfTaxThrowsExceptionWhenFinalDateIsLowerThanTheInitialDate()
    {
        $this->expectException(\Exception::class);

        $tax = new Tax();

        $tax->taxPercentage(
            '2021-01-01',
            '2020-12-01'
        );
    }

    public function testCalculationOfAmountOfTax()
    {
        $tax = new Tax();

        $amountTax = $tax->tax(
            '2021-01-01',
            '2021-02-02',
            '100000',
            '20000',
            '120000',
            '15000'
        );

        $this->assertIsArray($amountTax);
        $this->assertEquals(2500, $amountTax['applyTaxOn']);
    }

    public function testCalculationOfApplyTaxesOneYearInvestment()
    {
        $tax = new Tax();

        $taxation = $tax->calculate(
            '2021-01-01',
            '2022-02-02',
            '100000',
            '20000',
            '120000',
            '15000'
        );

        $this->assertIsArray($taxation);
        $this->assertEquals(2500, $taxation['applyTaxOn']);
        $this->assertEquals(0.185, $taxation['taxPercentage']);
        $this->assertEquals(4.63, $taxation['taxAmountToPay']);
    }


    public function testCalculationOfApplyTaxesTwoYearInvestment()
    {
        $tax = new Tax();

        $taxation = $tax->calculate(
            '2021-01-01',
            '2023-02-02',
            '100000',
            '20000',
            '120000',
            '15000'
        );

        $this->assertIsArray($taxation);
        $this->assertEquals(2500, $taxation['applyTaxOn']);
        $this->assertEquals(0.15, $taxation['taxPercentage']);
        $this->assertEquals(3.75, $taxation['taxAmountToPay']);
    }


    public function testCalculationOfApplyTaxesLowerthanoneYearInvestment()
    {
        $tax = new Tax();

        $taxation = $tax->calculate(
            '2021-01-01',
            '2021-10-02',
            '100000',
            '20000',
            '120000',
            '15000'
        );

        $this->assertIsArray($taxation);
        $this->assertEquals(2500, $taxation['applyTaxOn']);
        $this->assertEquals(0.225, $taxation['taxPercentage']);
        $this->assertEquals(5.63, $taxation['taxAmountToPay']);
    }

}