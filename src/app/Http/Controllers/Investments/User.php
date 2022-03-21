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
        $token = null; // Dont have a token
        
        return response()->json(["status" => $status], $http_code);
    }
    
    public function ListInvestments(Request $request) {
        $rd = json_decode($request->getContent()); // Request Data = rd

        $status = -1; // Generic error
        $http_code = 501; // Not implemented by default
        $token = null; // Dont have a token
        
        return response()->json(["status" => $status], $http_code);
    }
}
