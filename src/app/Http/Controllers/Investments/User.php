<?php

namespace App\Http\Controllers\Investments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class User extends Controller
{
    public function Create(Request $request) {
        $rd = json_decode($request->getContent()); // Request Data = rd

        $status = -1; // Generic error
        $http_code = 501; // Not implemented by default
        $id = null;

        try {
            $product = new \App\Models\InvestorUser;
            $product->name = $rd->name;
            $product->save();
            $id = $product->id;
            $status = 0; // Ok
            $http_code = 200; // Ok
        }catch(\Throwable $e){
            $status = -1; // Generic error
            $http_code = 500; // Internal error
        }
        
        return response()->json(["status" => $status, "owner_id" => $id], $http_code);
    }
    
    public function ListInvestments(Request $request) {
        $rd = json_decode($request->getContent()); // Request Data = rd

        $status = -1; // Generic error
        $http_code = 501; // Not implemented by default
        $investments = [];

        try {
            $investments = \App\Models\Investment::where("investor_user_id", $rd->user_id)->get()->transformWith(new \App\Transformers\InvestmentTransformer())->toArray()['data'];
            $status = 0; // Ok
            $http_code = 200; // Ok
        }catch(\Throwable $e){
            throw $e;
            $status = -1; // Generic error
            $http_code = 500; // Internal error
        }
        
        
        return response()->json(["status" => $status, "investments" => $investments], $http_code);
    }
}
