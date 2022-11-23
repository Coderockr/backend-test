<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvestmentRequest;
use App\Http\Requests\UpdateInvestmentRequest;
use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $investments = Investment::all();
        return response()->json(
            [
                'investments' => InvestmentResource::collection($investments),
            ],
            Response::HTTP_OK,
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInvestmentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreInvestmentRequest $request)
    {
        try {

            $data = $request->validated();
            $investment = Investment::create($data);
            $investment = Investment::with('movements')->findOrFail($investment->id);

            return response()->json(
                [
                    'data' => $investment,
                    'message' => 'Investment created!',
                ],
                Response::HTTP_CREATED,
            );

        } catch (\Exception $e){

            return response()->json(
                'Create investment failed',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $investment = Investment::with('person', 'movements')->findOrFail($id);

            return response()->json(
                [
                    'data' => new InvestmentResource($investment),
                ],
                Response::HTTP_OK,
            );

        } catch (ModelNotFoundException $m){

            return response()->json(
                'Investment not found',
                Response::HTTP_NOT_FOUND,
            );

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInvestmentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateInvestmentRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $investment = Investment::findOrFail($id);
            $investment->update($data);

            return response()->json(
                [
                    'data' => $investment,
                    'message' => 'Investment updated'
                ],
                Response::HTTP_ACCEPTED,
            );
        } catch (ModelNotFoundException $m){

            return response()->json(
                'Investment not found',
                Response::HTTP_NOT_FOUND,
            );

        } catch (\Exception $e){
            return response()->json(
                'Update investment failed',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            $investment = Investment::findOrFail($id);

            $investment->delete();

            return response()->json(
                [
                    'data' => $investment,
                    'message' => 'Investment deleted'
                ],
                Response::HTTP_OK,
            );

        } catch (ModelNotFoundException $m){

            return response()->json(
                'Investment not found',
                Response::HTTP_NOT_FOUND,
            );

        } catch (\Exception $e){
            return response()->json(
                'Delete investment failed',
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
