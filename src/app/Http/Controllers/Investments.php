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
            $investment->value = \App\Services\FinancialValueNodeTransformer::fromNodeToDatabaseInt($rd->investment_value);;
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

        $status = 0; // Ok
        $http_code = 200; // Ok
        
        return response()->json(["status" => $status], $http_code);
    }
    
    public function Withdrawal(Request $request) {
        $rd = json_decode($request->getContent()); // Request Data = rd

        $status = -1; // Generic error
        $http_code = 501; // Not implemented by default

        $status = 0; // Ok
        $http_code = 200; // Ok
        
        return response()->json(["status" => $status], $http_code);
    }
}
