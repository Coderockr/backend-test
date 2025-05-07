<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\Request;
use DateTimeZone;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    /**
     * Create new investment.
     *
     * @return \Illuminate\Http\Response
     */
    public function creation(Request $request){
        $dtz = new DateTimeZone("America/Fortaleza");
        $now = new DateTime("now", $dtz);

        $validator = Validator::make($request->all(), [
            'owner'                 => 'required|integer|gt:0',
            'initial_amount'        => 'required|numeric|gte:0',
            'creation'              => 'required|date|before_or_equal:' . $now->format("Y-m-d")
        ]);

        if ($validator->fails()) {
            return response(json_encode($validator->errors()), 400)
                            ->header('Content-Type', 'application/json');
        }

        $investmentId = Investment::create([
            'owner'                 => $request->owner,
            'creation'              => $request->creation,
            'initial_amount'        => $request->initial_amount,
        ]);

        if($investmentId){
            return response(["status" => "Success"], 200)->header('Content-Type', 'application/json');
        }
        else{
            return response(["status" => "Failed"], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * View one investment.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request){
        $validator = Validator::make($request->all(), [
            'investment'    => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response(json_encode($validator->errors()), 400)
                            ->header('Content-Type', 'application/json');
        }

        $investment = Investment::find($request->investment);

        if(!$investment){
            return response("Investment not found", 400)->header('Content-Type', 'application/json');
        }
        
        $expected_balance = $this->calcGains(Investment::find($request->investment));

        $response = [];
        $response['initial_amount'] = $investment->initial_amount;
        $response['expected_balance'] = $expected_balance;
        
        return response($response, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Return the expected balance of an investment.
     * 
     * @param Investmen Id of investment.
     * @return Float expected balance.
     */
    public function calcGains(Investment $investment, string $date = 'now'){
        $dtz = new DateTimeZone("America/Fortaleza");

        if($date == 'now'){
            $dateWithdraw = new DateTime("now", $dtz);
        }
        else{
            $dateWithdraw  = DateTime::createFromFormat('Y-m-d', $date)->setTimezone($dtz);
        }

        $creation_date = DateTime::createFromFormat('Y-m-d', $investment->creation)->setTimezone($dtz);

        $interval = $creation_date->diff($dateWithdraw);
        $months = intval($interval->format("%m"));
        $years = intval($interval->format("%y"));
        
        if($years != 0){
            $months += $years * 12;
        }

        $expected_balance = $investment->initial_amount;

        for($i = 0; $i < $months; $i++){
            $old_gains = $expected_balance;
            $new_gains = $old_gains * (0.52 / 100);
            $expected_balance += $new_gains;
        }

        return $expected_balance;
    }

    /**
     * Return the expected tax percentage of an investment.
     * 
     * @param Investmen Id of investment.
     * @return Float expected tax.
     */
    public function calcTaxes(Investment $investment, string $date){
        $dtz = new DateTimeZone("America/Fortaleza");
        $withdraw_date = DateTime::createFromFormat('Y-m-d', $date)->setTimezone($dtz);
        $creation_date = DateTime::createFromFormat('Y-m-d', $investment->creation)->setTimezone($dtz);
        
        $interval = $creation_date->diff($withdraw_date);
        $years = intval($interval->format("%y"));

        $tax_percentage = 0.0;
        if($years < 1){
            $tax_percentage = 22.5;
        }
        if($years >= 1 && $years < 2){
            $tax_percentage = 18.5;
        }
        if($years >= 2){
            $tax_percentage = 15.0;
        }

        return $tax_percentage;
    }

    /**
     * Withdraw an investment.
     *
     * @return \Illuminate\Http\Response
     */
    public function withdrawal(Request $request){
        $dtz = new DateTimeZone("America/Fortaleza");
        $now = new DateTime("now", $dtz);

        $investment = Investment::find($request->investment);

        if(!$investment){
            return response(["Error" => "Investment not found"], 400)->header('Content-Type', 'application/json');
        }

        $validator = Validator::make($request->all(), [
            'date'      => 'required|date|before_or_equal:' . $now->format("Y-m-d") . '|after_or_equal:' . $investment->creation,
            'investment'=> 'required|integer',
        ]);

        if ($validator->fails()) {
            return response(json_encode($validator->errors()), 400)
                            ->header('Content-Type', 'application/json');
        }

        $investment = Investment::find($request->investment);

        if(!$investment){
            return response(["Error" => "Investment not found"], 400)->header('Content-Type', 'application/json');
        }

        $expected_balance = $this->calcGains($investment, $request->date);
        $tax_percentage = $this->calcTaxes($investment, $request->date);

        $gains = $expected_balance - $investment->initial_amount;
        $taxes = $gains * ($tax_percentage / 100);

        $final_value = $gains - $taxes;
        $final_value += $investment->initial_amount;

        return response(['final_value' => $final_value], 200)->header('Content-Type', 'application/json');
    }

    /**
     * Withdraw an investment.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request){
        $validator = Validator::make($request->all(), [
            'page'  => 'required|integer|gt:0',
            'owner' => 'required|integer|gt:0',
        ]);

        if ($validator->fails()) {
            return response(json_encode($validator->errors()), 400)
                            ->header('Content-Type', 'application/json');
        }

        $limit = 5;
        $offset = $request->page - 1;

        $response = DB::table('investments')
                        ->where('owner', $request->owner)
                        ->limit($limit)
                        ->offset($offset * $limit)
                        ->get();

        return response($response, 200)->header('Content-Type', 'application/json');
    }
}