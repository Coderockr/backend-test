<?php

namespace App\Http\Controllers;

use App\Investor;
use Illuminate\Http\Request;

class InvestorController extends Controller
{

    public function showAllInvestors()
    {
        return response()->json(Investor::all());
    }

    public function showOneInvestor($id)
    {
        return response()->json(Investor::find($id));
    }

    public function createInvestor(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:investors',
            'email' => 'required|email',
        ]);

        $investor = Investor::create($request->all());

        return response()->json($investor, 201);
    }

    public function updateInvestor($id, Request $request)
    {
        $investor = Investor::findOrFail($id);
        $this->validate($request, [
            'name' => 'filled|unique:investors,name,' .$id,
            'email' => 'filled|email',
        ]);
        $investor->update($request->all());


        return response()->json($investor, 200);
    }

}