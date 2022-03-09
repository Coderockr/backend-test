<?php

namespace App\Http\Controllers;

use App\Investment;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{

    public function showAllInvestments()
    {
        return response()->json(Investment::all());
    }


    public function showOneInvestment($id)
    {
        $investment = Investment::findOrFail($id);
        if (!$investment['withdrawn']) {
            $investment['gain'] = $this->calculateGain($investment['amount_start'], $investment['date_creation']);
            $investment['amount_total'] = $investment['gain'] + $investment['amount_start'];
        }
        return response()->json($investment);
    }


    public function createInvestment(Request $request)
    {

        $this->validate($request, [
            'investor_id' => 'required|exists:investors,id',
            'date_creation' => 'required|date|before_or_equal:today|date_format:Y-m-d',
            'amount_start' => 'required|gt:0|regex:/^\d+(\.\d{1,2})?$/',
            'date_withdraw' => 'prohibited',
            'withdrawn' => 'prohibited',
            'amount_total' => 'prohibited',
            'gain' => 'prohibited',
            'tax' => 'prohibited'
        ]);

        $investment = Investment::create($request->all());

        return response()->json($investment, 201);
    }










    private function calculateGain($amount, $dateStart, $dateEnd = 'NOW')
    {
        $monthDiff = $this->monthDiff(new \DateTime($dateStart), new \DateTime($dateEnd));
        $count = 0;
        $amountWithGains =  $amount;
        while ($monthDiff > $count) {
            $amountWithGains += $this->formatMoney($amountWithGains * 0.0052);
            $count++;
        }
        return $this->formatMoney($amountWithGains - $amount);
    }


    private function formatMoney($amount) {
        return number_format(round($amount, 2), 2, '.', '' );
    }


    private function monthDiff(\DateTime $dateStart, \DateTime $dateEnd) {
        $diff = $dateEnd->diff($dateStart);
        return (($diff->y) * 12) + ($diff->m);
    }


}