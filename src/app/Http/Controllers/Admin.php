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

        $curentUser = \App::make(\App\Services\CurrentUser::class);

        try {
            if($curentUser->Login($rd->user, $rd->password)){
                $status = 0; // No error
                $http_code = 200; // Success
                $token = $curentUser->GetToken();
            }else{
                $status = 3; // Invalid user or password
                $http_code = 401; // Unauthorized
            }
        }catch(\Throwable $e){
            //throw $e;
            $status = -1; // Generic error
            $http_code = 500; // Internal error
        }

        return response()->json(["status" => $status, "token" => $token], $http_code);
    }
}
