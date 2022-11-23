<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Owner;
use Carbon\Carbon;

class InvestmentService
{
    CONST paymentPercentage = 0.52;

    public function show(array $data)
    {
        $investment = Investment::find($data['investment']);
        if ($investment->withdrawal_done) {
            // $message = "Final amount is {$investment->final_amount}, after withdrawal date {$investment->date_withdrawal}"
            // return response()->json([$message]);
            return;
        }

        $investmentExpected = $this->expectedAmount($investment);

        return response()->json(["Expected amount today for the investment is {$investmentExpected}"]);
    }

    public function create(array $data)
    {
        Investment::create([
            'initial_amount' => $data['amount'],
            'creation_date' => $data['creation_date'],
            'owner_id' => $data['owner_id']
        ]);

        return response()->json(['Created Investment!'], 201);
    }

    public function expectedAmount(Investment $investment)
    {
        $today = Carbon::parse();
        $monthsDiff = $today->diffInMonths($investment->creation_date);
        $amountExpected = $investment->initial_amount * ( (1 + self::paymentPercentage/100) ** ($monthsDiff - 1));

        return number_format($amountExpected, 4);
    }
}
