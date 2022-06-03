<?php

namespace  App\Domains\Home\Http\Controllers;

use App\Support\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function index()
    {
        return view('index');
    }
        
}
