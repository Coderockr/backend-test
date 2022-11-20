<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawStoreRequest;
use App\Models\Investment;
use App\Models\Withdraw;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    public function index(Request $request, $investmentId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Withdraw::query()->where('investment_id', $investmentId)->get()
        ]);
    }

    public function store(WithdrawStoreRequest $request, $investmentId)
    {
        $amount = $request->amount;
        $investment = Investment::query()->find($investmentId);

        if ($investment->withdraws) {
            return \response()->json([
                'success' => false,
                'message' => 'Investment already withdrawn'
            ], 422);
        }

        if ($amount && $investment->getTotalAmount() > $amount) {
            return response()->json([
                'success' => false,
                'message' => 'The investment amount must be withdraw integrally'
            ], 422);
        } else if (!$amount) {
            $amount = $investment->getTotalAmount();
        }

        $withdraw = Withdraw::query()->create([
            'amount' => $amount,
            'user_id' => Auth::id(),
            'investment_id' => $investmentId
        ]);

        return response()->json([
            'success' => true,
            'data' => $withdraw
        ], 201);
    }
}
