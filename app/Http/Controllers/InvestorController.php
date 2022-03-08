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
            'name' => 'required|unique:investors'
        ]);

        $investor = Investor::create($request->all());

        return response()->json($investor, 201);
    }

    public function updateInvestor($id, Request $request)
    {
        $investor = Investor::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|unique:investors,name,' .$id
        ]);
        $investor->update($request->all());


        return response()->json($investor, 200);
    }

}