<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Investments extends Controller
{
    public function Create(Request $request) {
        $rd = json_decode($request->getContent()); // Request Data = rd

        $status = -1; // Generic error
        $http_code = 501; // Not implemented by default
        $id = null;

        try {
            $investment = new \App\Models\Investment;
            $investment->value = \App\Services\FinancialValueNodeTransformer::fromNodeToDatabaseInt($rd->investment_value);
            $investment->investment_timestamp = \App\Services\DateTimeNodeTransformer::fromNodeToDateTime($rd->creation_date);
            $investment->investor_user_id = $rd->owner_id;
            $investment->save();
            $id = $investment->id;
            $status = 0; // Ok
            $http_code = 200; // Ok
        }catch(\Throwable $e){
            //throw $e;
            $status = -1; // Generic error
            $http_code = 500; // Internal error
        }
        
        
        return response()->json(["status" => $status, "investment_id" => $id], $http_code);
    }

    public function View(Request $request) {
        $rd = json_decode($request->getContent()); // Request Data = rd

        $status = -1; // Generic error
        $http_code = 501; // Not implemented by default
        $investment = null;

        try {
            $investment = \App\Models\Investment::where("id", $rd->investment_id)->get()->transformWith(new \App\Transformers\InvestmentTransformer($rd->decimals, $rd->format, false, false))->toArray()['data'][0];
            $status = 0; // Ok
            $http_code = 200; // Ok
        }catch(\Throwable $e){
            throw $e;
            $status = -1; // Generic error
            $http_code = 500; // Internal error
        }
        
        return response()->json(array_merge(["status" => $status], $investment), $http_code);
    }
    
    public function Withdrawal(Request $request) {
        $rd = json_decode($request->getContent()); // Request Data = rd

        $status = -1; // Generic error
        $http_code = 501; // Not implemented by default
        $investmentOutput = null;

        try {
            $investment = \App\Models\Investment::where("id", $rd->investment_id)->first();
            $investment->withdraw_timestamp = new \DateTime();
            $investment->save();
            \DB::commit();
            $investmentOutput = \App\Models\Investment::where("id", $rd->investment_id)->get()->transformWith(new \App\Transformers\InvestmentTransformer($rd->decimals, $rd->format, false, true))->toArray()['data'][0];
            $status = 0; // Ok
            $http_code = 200; // Ok
        }catch(\Throwable $e){
            throw $e;
            $status = -1; // Generic error
            $http_code = 500; // Internal error
        }
        
        return response()->json(array_merge(["status" => $status], $investmentOutput), $http_code);
    }
}
