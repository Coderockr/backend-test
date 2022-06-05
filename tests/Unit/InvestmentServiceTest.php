<?php

namespace Tests\Unit;

use App\Models\Investment;
use PHPUnit\Framework\TestCase;

use App\Services\InvestmentService;
use Carbon\Carbon;

class InvestmentServiceTest extends TestCase
{
    public function test_if_truncate_value_to_two_decimal_places_is_working() {
        $investmentService = new InvestmentService();

        $this->assertEquals(5.14, $investmentService->truncateValueToTwoDecimalPlaces(5.1431)); 
    }

    public function test_if_reset_hours_minutes_and_seconds_from_date_time_is_working() {
        $investmentService = new InvestmentService();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2000-05-19 12:05:11');
        $dateResetted = $investmentService->resetHoursMinutesAndSecondsFromDateTime($date);

        $this->assertEquals('2000-05-19 00:00:00', $dateResetted->toDateTimeString());
    }

    public function test_if_get_difference_of_dates_in_months_with_dates_in_the_same_day_is_working() {
        $investmentService = new InvestmentService();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2000-05-19 12:05:11');
        $date2 = Carbon::createFromFormat('Y-m-d H:i:s', '2000-07-19 12:05:11');

        $diffInMonths = $investmentService->getDifferenceOfDatesInMonths($date, $date2);

        $this->assertEquals(2, $diffInMonths);        
    }

    public function test_if_get_difference_of_dates_in_months_with_dates_in_different_day_is_working() {
        $investmentService = new InvestmentService();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2000-05-19 12:05:11');
        $date2 = Carbon::createFromFormat('Y-m-d H:i:s', '2000-06-18 12:05:11');

        $diffInMonths = $investmentService->getDifferenceOfDatesInMonths($date, $date2);

        $this->assertEquals(0, $diffInMonths);        
    }

    public function test_if_get_difference_of_dates_in_months_with_dates_with_negative_diff_is_working() {
        $investmentService = new InvestmentService();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2000-08-31 12:05:11');
        $date2 = Carbon::createFromFormat('Y-m-d H:i:s', '2000-05-31 12:05:11');

        $diffInMonths = $investmentService->getDifferenceOfDatesInMonths($date, $date2);

        $this->assertEquals(3, $diffInMonths);        
    }

    public function test_if_get_difference_of_dates_in_months_in_different_years_is_working() {
        $investmentService = new InvestmentService();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2004-08-31 12:05:11');
        $date2 = Carbon::createFromFormat('Y-m-d H:i:s', '2000-05-31 12:05:11');

        $diffInMonths = $investmentService->getDifferenceOfDatesInMonths($date, $date2);

        $this->assertEquals(51, $diffInMonths);        
    }

    public function test_if_get_difference_of_dates_in_years_birthday_date_is_working() {
        $investmentService = new InvestmentService();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2001-08-31 12:05:11');
        $date2 = Carbon::createFromFormat('Y-m-d H:i:s', '2004-08-31 12:05:11');

        $diffInYears = $investmentService->getDifferenceOfDatesInYears($date, $date2);

        $this->assertEquals(3, $diffInYears); 
    }

    public function test_if_get_difference_of_dates_in_years_is_working() {
        $investmentService = new InvestmentService();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2001-08-29 12:05:11');
        $date2 = Carbon::createFromFormat('Y-m-d H:i:s', '2000-08-30 12:05:11');

        $diffInYears = $investmentService->getDifferenceOfDatesInYears($date, $date2);

        $this->assertEquals(0, $diffInYears); 
    }

    public function test_if_get_difference_of_dates_in_years_with_dates_with_negative_diff_is_working() {
        $investmentService = new InvestmentService();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2000-08-20 12:05:11');
        $date2 = Carbon::createFromFormat('Y-m-d H:i:s', '2001-08-20 12:05:11');

        $diffInYears = $investmentService->getDifferenceOfDatesInYears($date, $date2);

        $this->assertEquals(1, $diffInYears); 
    }

    public function test_if_calculate_expected_balance_is_working() {
        $investmentService = new InvestmentService();

        $this->assertEquals(103.03, $investmentService->calculateExpectedBalance(100, 0.01, 3)); 
    }

    public function test_if_get_expected_balance_is_working() {
        $investmentService = new InvestmentService();

        $investment = new Investment;
        $investment->amount = 2532.12;
        $investment->inserted_at = '2000-08-31';

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2000-11-31 12:05:11');

        $this->assertEquals(2726.81, $investmentService->getExpectedBalance($investment, 0.025, $date)); 
    }

    public function test_if_get_tax_value_more_than_two_years_is_working() {
        $investmentService = new InvestmentService();

        $this->assertEquals(0.15, $investmentService->getTaxValue(3));         
    }

    public function test_if_get_tax_value_between_one_and_two_years_is_working() {
        $investmentService = new InvestmentService();
        $this->assertEquals(0.185, $investmentService->getTaxValue(1));         
    }

    public function test_if_get_tax_value_between_less_than_one_year_is_working() {
        $investmentService = new InvestmentService();

        $this->assertEquals(0.225, $investmentService->getTaxValue(0));         
    }

    public function test_if_calculate_money_to_reduce_from_gain_is_working() {
        $investmentService = new InvestmentService();

        $this->assertEquals(0.12, $investmentService->calculateMoneyToReduceFromGain(0.5, 0.25));           
    }

    public function test_if_calculate_investment_return_value_is_working() {
        $investmentService = new InvestmentService();

        $this->assertEquals(49.04, $investmentService->calculateInvestmentReturnValue(50, 0.9572));           
    }

    public function test_if_investment_return_is_working() {
        $investmentService = new InvestmentService();

        $investment = new Investment;
        $investment->amount = 200.20;
        $investment->inserted_at = '2000-08-31';

        $gainValue = 0.12;

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2000-11-31 12:05:11'); 
        $this->assertEquals(263.02, $investmentService->getInvestmentReturn($investment, $gainValue, $date));        
    }
}
