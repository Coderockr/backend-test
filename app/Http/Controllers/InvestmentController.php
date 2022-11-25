<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentPostRequest;
use App\Http\Requests\WithdrawRequest;
use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use App\Services\WithdrawServices;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function index(Investment $eloquent)
    {
        $investments = $eloquent->newQuery()->paginate();

        return InvestmentResource::collection($investments);
    }

    public function show(string $id)
    {
        $investment = Investment::with('person')->find($id);

        if (! $investment) {
            return response()->json([
                'data'  =>  [
                    'success'   =>  false,
                    'message'   =>  'Investment not found'
                ]
            ], 404);
        }

        return new InvestmentResource($investment);
    }

    public function store(InvestmentPostRequest $request)
    {
        $data = Investment::create($request->all());
        $investment = Investment::with('movements')->find($data->id);

        return new InvestmentResource($investment);
    }

    public function withdraw(WithdrawRequest $request, $investmentId)
    {
        $investment = Investment::find($investmentId);
        $withdrawalDate = Carbon::parse($request->input('date'));

        if (! $investment) {
            return response()->json([
                'data'  =>  [
                    'success'   =>  false,
                    'message'   =>  'Investment not found'
                ]
            ], 404);
        }

        if ($investment->withdraw) {
            return response()->json([
                'data'  =>  [
                    'success'   =>  false,
                    'message'   =>  'This investment has already been withdrawn'
                ]
            ], 403);
        }


        try {
            $service = new WithdrawServices($investment, $withdrawalDate);
            $service->withdraw();

            return new InvestmentResource($investment);

        } catch (\Exception $exception) {
            return response()->json([
                'data'  =>  [
                    'success'   =>  false,
                    'message'   =>  'The investment withdrawn  can\'t happen before the investment creation date or future date'
                ]
            ], 403);
        }
    }
}
