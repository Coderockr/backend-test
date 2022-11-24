<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvestmentMovementRequest;
use App\Http\Requests\UpdateInvestmentMovementRequest;
use App\Http\Resources\InvestmentMovementResource;
use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use App\Models\InvestmentMovement;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class InvestmentMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($investment_id)
    {
        try {
            $investment = Investment::with('movements')->findOrFail($investment_id);

            return response()->json(
                [
                    'investment' => new InvestmentResource($investment->withoutRelations()),
                    'movements' => InvestmentMovementResource::collection($investment->movements),
                ],
                Response::HTTP_OK,
            );

        } catch (ModelNotFoundException $m){
            return response()->json(
                [
                    'message' => 'Investment Not Found'
                ],
                Response::HTTP_NOT_FOUND,
            );
        }
    }
}
