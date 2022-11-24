<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentWithdrawnRequest;
use App\Jobs\SendWithdrawnalProof;
use App\Models\Investment;
use App\Models\InvestmentMovement;
use App\Services\InvestmentWithdrawnService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class InvestmentWithdrawnController extends Controller
{
    protected InvestmentWithdrawnService $investmentWithdrawnService;

    public function __construct(){
        $this->investmentWithdrawnService = new InvestmentWithdrawnService();
    }

    public function withdrawn(InvestmentWithdrawnRequest $request, $id){

        DB::beginTransaction();
        try {

            $investment = Investment::with('person')->findOrFail($id);
            $withdrawn_at = $request->validated()['withdrawn_at'];

            if(!$this->investmentWithdrawnService->isWithdrawnDateValid($investment, $withdrawn_at)){
                return response()->json(
                    [
                        'message' => "Invalid withdrawn date. 
                            Withdrawn date can't be before the investment creation or the future"
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            if($investment->is_withdrawn){
                return response()->json(
                    [
                        'message' => "This investment has already been withdrawn"
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $investment->update([
               'withdrawn_at' => $withdrawn_at,
               'is_withdrawn' => 1,
            ]);

            $calculatedTax = $this->investmentWithdrawnService->calculateWithdrawnTaxes($investment, $withdrawn_at);
            $withdrawnValue = floatval($investment->initial_investment + $investment->investment_profit - $calculatedTax);

            $investment->movements()->createMany([
                [
                    'description' => 'Withdrawn Taxes',
                    'value' => $calculatedTax,
                    'movement_at' => $withdrawn_at,
                    'type' => InvestmentMovement::TYPE_TAX,
                ],
                [
                    'description' => 'Withdrawn',
                    'value' =>  $withdrawnValue,
                    'movement_at' => $withdrawn_at,
                    'type' => InvestmentMovement::TYPE_WITHDRAWN,
                ],
            ]);

            dispatch(new SendWithdrawnalProof($investment));

            DB::commit();
            return response()->json(
                [
                    'message' => 'The investment has been withdrawn'
                ],
                Response::HTTP_ACCEPTED,
            );

        } catch (ModelNotFoundException $m){

            return response()->json(
                [
                    'message' => 'Investment not found',
                ],
                Response::HTTP_NOT_FOUND,
            );
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json(
                'Withdrawal of investment failed',
                Response::HTTP_BAD_REQUEST
            );
        }
    }


}
