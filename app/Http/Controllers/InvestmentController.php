<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Models\Investment as Investment;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Investment as InvestmentResource;

class InvestmentController extends Controller
{
    /**
     * List all investments
     *
     * @return \App\Http\Resources
     */
    public function index(){
        // list all investments, 10 per page
        $investments = Investment::paginate(10);
        return InvestmentResource::collection($investments);
    }

    /**
     * Create a new investment with an owner, a creation date and an amount.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Http\Resources
     */
    public function creation(Request $request){
        // create investments with owner, amount and create date
        $investment = new Investment;
        $investment->owner = $request->input('owner');
        $investment->amount = $request->input('amount');
        $investment->create_date = $request->input('create_date');
        
        // validation to create a new investment
        $now = new DateTime("now");
        $validate = Validator::make($request->all(), [
            'owner'         => 'required',
            'amount'        => 'required|numeric|gte:0',
            'create_date'   => 'required|date|before_or_equal:' . $now->format("Y-m-d")
        ]);

        // show a error in case of validation fail
        if ($validate->fails()) {
            return response(json_encode($validate->errors()), 400)
                            ->header('Content-Type', 'application/json');
        }

        // save a new investment
        if( $investment->save() ){
          return new InvestmentResource($investment);
        }
    }

    /**
     * View of an investment with its initial amount and expected balance.
     *
     * @param int $id
     * @return \App\Http\Resources
     */
    public function view($id){
        // check if investment exists
        $investment = Investment::findOrFail($id);
        $resource = new InvestmentResource($investment);

        // calculate expected balance
        $expectedBalance = $this->calculateGains($investment);
        $investment['expectedBalance'] = (float) number_format($expectedBalance, 2, '.', '');

        return $resource->toArrayInvest($investment);
    }

    /**
     * Calculate the gains each month, expected balance should be the sum of the invested amount and the gains.
     * 
     * @param Investment $investment
     */
    public function calculateGains(Investment $investment){
        // get all dates needed
        $date = $this->getDates($investment);

        // check if create date of investment and date for expected balanced are equals
        if($investment->create_date == $date['balanceDate']->format("Y-m-d")){
            return $investment->amount;
        }

        // calculate expected balance for all months of investment
        $expectedBalance = $investment->amount;
        for($i = 0; $i < $date['months']; $i++){
            $gain = $expectedBalance * (0.52 / 100);
            $expectedBalance += $gain;
        }

        return $expectedBalance;
        
    }

    /**
     * Get dates to use on function calculateGains and returns date of investment, date of balance and how much months and years of investment already passed.
     * 
     * @param Investment $investment
     */
    public function getDates(Investment $investment){
        // find the interval of the investment
        $investDate = new DateTime($investment->create_date);
        $balanceDate = new DateTime(date("Y-m-d"));
        $interval = $investDate->diff($balanceDate);
        $months = $interval->m;
        $years = $interval->y;
        if($years > 0){
            $months += $years * 12;
        }   

        // array with all info of date
        $date = array('investDate' => $investDate, 'balanceDate' => $balanceDate, 'months' => $months, 'years' => $years);

        return $date;
    }

    /**
     * Calculate the value withdrawal of a investment.
     * 
     * @param int $id
     * @param Investment $investment
     * @return \App\Http\Resources
     */
    public function withdrawal($id, Request $request){
        // check if investment exists
        $investment = Investment::findOrFail($id);
        $resource = new InvestmentResource($investment);

        //calculate taxes for withdrawal value
        $withdrawalValue = $this->calculateTaxation($investment, $request);
        $investment['withdrawalValue'] = (float) number_format($withdrawalValue, 2, '.', '');
        $investment['withdrawalDate'] = $request->withdrawal_date->format("Y-m-d");
        
        return $resource->toArrayWithdrawal($investment);
    }

    /**
     * Calculate the taxation of a withdrawal.
     * 
     * @param Investment $investment
     * @param Request $request
     */
    public function calculateTaxation($investment, Request $request){
        // get infos to calculate taxes and gain
        $expectedBalance = $this->calculateGains($investment);
        $investment['expectedBalance'] = (float) number_format($expectedBalance, 2, '.', '');
        $taxPercent = $this->checkTax($investment, $request);
        $investment['taxPercent'] = (float) number_format($taxPercent, 1, '.', '');
        $gain = $expectedBalance - $investment->amount;
        $taxes = $gain * ($taxPercent / 100);
        $withdrawalValue = $investment->amount + $gain - $taxes;
        return $withdrawalValue;
    }

    /**
     * Check the taxes for a investment.
     * 
     * @param Investment $investment
     * @param Request $request
     */
    public function checkTax(Investment $investment, Request $request){
        // get all dates needes
        $date = $this->getDates($investment);

        // set the withdrawal date today if null
        if($request->withdrawal_date == null){
            $request->withdrawal_date = new DateTime("now");
        }

        // validation to date of a withdrawal
        $validate = Validator::make($request->all(), [
            'withdrawal_date'      => 'date|before_or_equal:' . $request->withdrawal_date->format("Y-m-d") . '|after_or_equal:' . $investment->create_date
        ]);

        // show a error in case of validation fail
        if ($validate->fails()) {
            return response(json_encode($validate->errors()), 400)
                            ->header('Content-Type', 'application/json');
        }

        //check which tax will be charged
        if($date['years'] < 1){
            $taxPercent = 22.5;
        }elseif($date['years'] >= 1 && $date['years'] < 2){
            $taxPercent = 18.5;
        }else{
            $taxPercent = 15;
        }
        return $taxPercent;
    }

    /**
     * List all person's investments
     * 
     * @param int $owner
     * @return \App\Http\Resources
     */
    public function list($owner){
        // query to find if the owner exists
        $investments = Investment::findOrFail($owner)->where('owner', $owner)->paginate(10);
    
        return InvestmentResource::collection($investments);
    }
}
