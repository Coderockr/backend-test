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
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $investments = Investment::paginate(10);
        return InvestmentResource::collection($investments);
    }

    /**
     * create a new investment with an owner, a creation date and an amount.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function creation(Request $request){
        $investments = new Investment;
        $investments->owner = $request->input('owner');
        $investments->amount = $request->input('amount');
        $investments->create_date = $request->input('create_date');
        
        $now = new DateTime("now");
        $validate = Validator::make($request->all(), [
            'owner'         => 'required',
            'amount'        => 'required|numeric|gte:0',
            'create_date'   => 'required|date|before_or_equal:' . $now->format("Y-m-d")
        ]);

        if ($validate->fails()) {
            return response(json_encode($validate->errors()), 400)
                            ->header('Content-Type', 'application/json');
        }

        if( $investments->save() ){
          return new InvestmentResource($investments);
        }
    }

    /**
     * View of an investment with its initial amount and expected balance.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id){
        try{
            $investments = Investment::find($id);
            $resource = new InvestmentResource($investments);
            $expectedBalance = $this->calculateGains(Investment::find($id));
            $investments['expected_balance'] = (float) number_format($expectedBalance, 2, '.', '');
            return $resource->toArrayInvest($investments);
        }catch(\Exception $e){
            return $resource->toArrayNotFound($e);
        }
    }

    /**
     * Calculate the gains each month, expected balance should be the sum of the invested amount and the gains.
     * 
     */
    public function calculateGains(Investment $investment){
        $date = $this->getDates($investment);
        if($investment->create_date == $date['balanceDate']->format("Y-m-d")){
            return $investment->amount;
        }
        $expectedBalance = $investment->amount;
        for($i = 0; $i < $date['months']; $i++){
            $gain = $expectedBalance * (0.52 / 100);
            $expectedBalance += $gain;
        }
        return $expectedBalance;
        
    }

    /**
     * Get dates to use on function calculateGains and returns date of investment, date of balance and how much months of investment already passed
     * 
     */
    public function getDates(Investment $investment){
        $investDate = new DateTime($investment->create_date);
        $balanceDate = new DateTime(date("Y-m-d"));
        $interval = $investDate->diff($balanceDate);
        $months = $interval->m;
        $years = $interval->y;
        if($years > 0){
            $months += $years * 12;
        }   
        $date = array('investDate' => $investDate, 'balanceDate' => $balanceDate, 'months' => $months);
        return $date;
    }
}
