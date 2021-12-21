<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index() : JsonResponse
    {
        return response()->json(User::all(), 200);
    }

    public function store(UserRequest $request) : JsonResponse
    {
        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    public function update(UserRequest $request, User $user) : JsonResponse
    {
        $user->update($request->all());

        return response()->json($user, 201);
    }

    public function destroy(User $user) : JsonResponse
    {
        $user->delete();

        return response()->json([], 204);
    }

}
