<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentPostRequest;
use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvestmentController extends Controller
{
    public function index()
    {
        return InvestmentResource::collection(Investment::all());
    }

    public function show(string $id)
    {
        $investment = Investment::find($id);

        return new InvestmentResource($investment);
    }

    public function store(InvestmentPostRequest $request)
    {
        $data = $request->all();

        return Investment::create($data);


    }
}
