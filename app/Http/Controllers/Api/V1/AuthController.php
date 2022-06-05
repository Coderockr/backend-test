<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;

class AuthController extends Controller
{
    /**
     * Register a user give it a token.
     *
     * @param  \App\Http\Requests\RegisterUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterUserRequest $request) : Response {
        $validatedData = $request->all();
        $validatedData['password'] = bcrypt($validatedData['password']);

        // Creating the user with the validated data
        $user = User::create($validatedData);

        // Creating a token assigned to the user
        $token = $user->createToken('backend-test-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        // Returning the response with the corrected status
        return response($response, 201);
    }

    /**
     * Login a user give it a token.
     *
     * @param  \App\Http\Requests\RegisterUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request) {//: Response {
        // Check email
        $user = User::where('email', $request->email)->first();

        // Check password and if the user exists
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        // Creating a token assigned to the user
        $token = $user->createToken('backend-test-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        // Returning the response with the corrected status
        return response($response, 201);
    }

    /**
     * Logout a user and delete its tokens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) : Response {
        // Deleting the authenticate user tokens
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged out'
        ], 200);
    }
}
