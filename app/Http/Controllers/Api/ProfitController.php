<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    public function index(Request $request, $investmentId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Profit::query()->where('investment_id', $investmentId)->get()
        ]);
    }

    public function store(Request $request, $investmentId): JsonResponse
    {
        $profit = Profit::query()->create([
            'amount' => $request->amount,
            'investment_id' => $investmentId
        ]);

        return response()->json([
            'success' => true,
            'data' => $profit
        ], 201);
    }
}
