<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Owner;
use Carbon\Carbon;

class InvestmentService
{
    CONST paymentPercentage = 0.52;
    CONST taxLessThanOneYearPercentage = 22.5;
    CONST taxBetweenOneAndTwoYearsPercentage = 18.5;
    CONST taxOlderThanTwoYearsPercentage = 15.0;

    public function show(array $data)
    {
        $investment = Investment::find($data['investment']);
        if ($investment->withdrawal_done) {
            $message = "Final amount is {$investment->final_amount}, after withdrawal done";
            return response()->json($message);
        }

        $today = Carbon::parse();
        $gains = $this->calculateGains($investment, $today);
        $taxes = $this->calculateTaxes($investment->creation_date, $gains, $today);

        $investmentExpected = ($investment->initial_amount + $gains - $taxes);

        return response()->json([
            'initial_amount' =>  $investment->initial_amount,
            'expected_amount' => $investmentExpected,
            'creation_date' => $investment->creation_date,
            'gains_at_the_moment' => $gains
        ]);
    }

    public function create(array $data)
    {
        $investment = Investment::create([
            'initial_amount' => $data['amount'],
            'creation_date' => $data['creation_date'],
            'owner_id' => $data['owner_id']
        ]);

        return response()->json("Created Investment - id: {$investment->id}", 201);
    }

    public function withdrawal(array $data)
    {
        $investment = Investment::find($data['investment']);
        $date_withdrawal = $data['withdrawal_date'];
        
        if ($investment->withdrawal_done) {
            return response()->json('Already done', 401);
        }

        if ($this->verifyDate($date_withdrawal, $investment->creation_date)) {
            return response()->json('Date invalid, pass date after investment creation', 401);
        }

        $gains = $this->calculateGains($investment, $date_withdrawal);
        $taxes = $this->calculateTaxes($investment->creation_date, $gains, $date_withdrawal);

        $investment->gains = $gains;
        $investment->taxes = $taxes;
        $investment->final_amount = ($investment->initial_amount + $gains - $taxes);
        $investment->withdrawal_date = $date_withdrawal;
        $investment->withdrawal_done = true;
        $investment->save();

        return response()->json($investment->details, 201);
    }

    public function verifyDate($date_withdrawal, $creation_date)
    {
        return strtotime($date_withdrawal) <= strtotime('+1 month', strtotime($creation_date));
    }

    public function calculateGains(Investment $investment, $final_date)
    {
        $startDate = Carbon::parse($investment->creation_date);
        $endDate = Carbon::parse($final_date);
        $monthsDiff = $startDate->diffInMonths($endDate);
        $initial_amount = $investment->initial_amount;
        
        return $initial_amount * ( (1 + self::paymentPercentage/100) ** ($monthsDiff)) - $initial_amount;
    }

    public function calculateTaxes($creation_date, $gains, $withdrawal_date)
    {
        $startDate = Carbon::parse($creation_date);
        $endDate = Carbon::parse($withdrawal_date);
        $yearsDiff = $startDate->diffInYears($endDate);

        if ($yearsDiff < 1) {
            return $gains * (self::taxLessThanOneYearPercentage/100);
        } elseif ($yearsDiff  <= 2) {
            return $gains * (self::taxBetweenOneAndTwoYearsPercentage/100);
        } else {
            return $gains * (self::taxOlderThanTwoYearsPercentage/100);
        }
    }
}
