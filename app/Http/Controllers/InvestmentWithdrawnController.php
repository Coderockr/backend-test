<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentWithdrawnRequest;
use App\Models\Investment;
use App\Services\InvestmentWithdrawnService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class InvestmentWithdrawnController extends Controller
{
    protected InvestmentWithdrawnService $investmentWithdrawnService;

    public function __construct(){
        $this->investmentWithdrawnService = new InvestmentWithdrawnService();
    }

    public function withdrawn(InvestmentWithdrawnRequest $request, $id){

        try {

            $investment = Investment::findOrFail($id);
            $withdrawn_at = $request->validated()['withdraw_at'];

            if(!$this->investmentWithdrawnService->isWithdrawnDateValid($investment, $withdrawn_at)){
                return response()->json(
                    [
                        'message' => "Invalid withdrawn date. 
                            Withdrawn date can't be before the investment creation or the future"
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }



        } catch (ModelNotFoundException $m){
            return response()->json(
                [
                    "message' => 'Investment not found',
                ],
                Response::HTTP_NOT_FOUND,
            );
        }
    }


}
