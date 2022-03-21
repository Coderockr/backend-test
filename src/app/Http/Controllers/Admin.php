<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Admin extends Controller
{
    public function Login(Request $request) {
        $rd = json_decode($request->getContent()); // Request Data = rd

        $status = -1; // Generic error
        $http_code = 501; // Not implemented by default
        $token = null; // Dont have a token
        
        if($rd->user == "mocked" && $rd->password == "with mock data"){
            $status = 0; // No error
            $http_code = 200; // Success
            $token = \App\Services\JWT::encode(['id' => 1, 'mock' => 1]);
        }else{
            $status = 3; // Invalid user or password
            $http_code = 401; // Unauthorized
        }
        return response()->json(["status" => $status, "token" => $token], $http_code);
    }
}
