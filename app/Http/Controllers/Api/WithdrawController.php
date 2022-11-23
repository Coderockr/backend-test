<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawStoreRequest;
use App\Models\Investment;
use App\Models\Withdraw;
use Carbon\Carbon;
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

    public function store(WithdrawStoreRequest $request, $investmentId): JsonResponse
    {
        $amount = $request->amount;
        $investment = Investment::query()->find($investmentId);
        $totalAmount = $investment->getTotalAmount();
        $dateDiff = now()->diffInDays(Carbon::parse($investment->date));
        $amountProfit = $investment->profits()->sum('amount');

        if ($investment->withdraws) {
            return response()->json([
                'success' => false,
                'message' => 'Investment already withdrawn'
            ], 422);
        }

        if ($amount && $totalAmount > $amount) {
            return response()->json([
                'success' => false,
                'message' => 'The investment amount must be withdraw integrally'
            ], 422);
        } else if ($amount && $totalAmount < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Your amount on balance is below the value you tried to withdraw! ' . 'Your balance: ' . $totalAmount
            ], 422);
        } else if (!$amount) {
            $amount = $totalAmount;
        }


        $withdraw = Withdraw::query()->create([
            'gross_value' => $amount,
            'net_value' => $amount - $this->getWithdrawTax($dateDiff, $amountProfit),
            'tax' => $this->getWithdrawTax($dateDiff, $amountProfit),
            'user_id' => Auth::id(),
            'investment_id' => $investmentId
        ]);

        return response()->json([
            'success' => true,
            'data' => $withdraw
        ], 201);
    }

    public function getWithdrawTax($days, $amount): int|float
    {
        return match ($days) {
            $days <= 365 => round(($amount / 100) * 22.5, 2),
            $days <= 730 => round(($amount / 100) * 18.5, 2),
            default => round(($amount / 100) * 15, 2),
        };
    }
}
