<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawStoreRequest;
use App\Models\Investment;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Withdraw::query()->where('user_id', Auth::id())
        ]);
    }

    public function store(WithdrawStoreRequest $request)
    {
        if ($request->amount) {
            $investment = Investment::query()->find($request->investment_id);
            if ($investment->getTotalAmount() > $request->amount) {
                return response()->json([
                   'success' => false,
                   'message' => 'The investment amount must be withdraw integrally'
                ], 422);
            }
        }
        $withdraw = Withdraw::query()->create([
            'amount' => $request->amount,
            'user_id' => Auth::id(),
            'investment_id' => $request->investment_id
        ]);

        return response()->json([
            'success' => true,
            'data' => $withdraw
        ], 201);
    }
}
