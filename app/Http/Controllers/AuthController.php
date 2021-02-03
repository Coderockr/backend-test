<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'min:4'],
            'email' => ['required', 'unique:users', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        $token = $user->createToken('CoderockrToken')->accessToken;

        return response()->json(['token' => $token]);
    }

    public function login(Request $request)
    {
        $data = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('CoderockrToken')->accessToken;

            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
