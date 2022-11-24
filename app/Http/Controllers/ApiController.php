<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    public function welcome(){

        return response()->json(
            [
                'message' => 'Welcome to Coderockr backend test api',
                'api_version' => 'v1',
                'api_docs' => 'http://localhost:8100/'
            ],
            Response::HTTP_OK,
        );
    }
}
