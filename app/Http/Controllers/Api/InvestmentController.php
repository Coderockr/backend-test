<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvestmentStoreRequest;
use App\Models\Investment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Investment::query()->where('user_id', Auth::id())->get(),
        ]);
    }

    public function store(InvestmentStoreRequest $request): JsonResponse
    {
        $investment = Investment::query()->create([
            'amount' => $request->amount,
            'user_id' => Auth::id(),
            'date' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $investment
        ], 201);
    }
}
