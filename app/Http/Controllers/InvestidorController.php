<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestidorRequest;
use App\Models\Investidor;

class InvestidorController extends Controller
{
    public function create(InvestidorRequest $request)
    {
        $investidor = Investidor::create($request->only('email'));
        return response()->json($investidor, 201);
    }
}
