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


    public function showAllInvestmentsByInvestor($investor_id)
    {
        $investments = Investment::where('investor_id', '=', $investor_id )->Paginate(3);
        return response()->json($investments);
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


    public function withdrawInvestment($id, Request $request)
    {
        $investment = Investment::findOrFail($id);
        $this->validate($request, [
            'date_withdraw' => "required|date|before_or_equal:today|after_or_equal:{$investment['date_creation']}|date_format:Y-m-d",
        ]);
        if ($investment['withdrawn']) {
            return response()->json(['error' => 'The investment was already withdraw'], 407);
        }

        $update = array();

        $update['withdrawn'] = true;
        $update['date_withdraw'] = $request['date_withdraw'];
        $update['gain'] = $this->calculateGain($investment['amount_start'], $investment['date_creation'], $request['date_withdraw']);
        $update['tax'] = $this->calculateTax($update['gain'], $investment['date_creation'], $request['date_withdraw']);
        $update['amount_total'] = $this->formatMoney($update['gain'] + $investment['amount_start']);
        $update['amount_withdrawn'] =  $this->formatMoney($update['amount_total'] - $update['tax']);

        $investment->update($update);

        return response()->json($investment, 200);

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

    private function calculateTax($amount, $dateStart, $dateEnd)
    {
        $monthDiff = $this->monthDiff(new \DateTime($dateStart),  new \DateTime($dateEnd));
        if ($monthDiff < 12) {
            $taxRate = 0.225;
        } elseif ($monthDiff < 24) {
            $taxRate = 0.185;
        } else {
            $taxRate = 0.15;
        }
        return $this->formatMoney($amount * $taxRate);
    }





    private function formatMoney($amount) {
        return number_format(round($amount, 2), 2, '.', '' );
    }


    private function monthDiff(\DateTime $dateStart, \DateTime $dateEnd) {
        $diff = $dateEnd->diff($dateStart);
        return (($diff->y) * 12) + ($diff->m);
    }


}