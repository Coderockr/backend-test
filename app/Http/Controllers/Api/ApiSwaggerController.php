<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiSwaggerController extends Controller
{
    public function getSwagger()
    {
        return view('swagger.index');
    }
}
