<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    public function __construct() {
        request()->headers->set('Accept', 'application/json');
    }
    */
    
    public function register(Request $request) {
        // Validating the request
        // When passing confirmed to validator, password_confirm is required too
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed' 
        ]);

        // Creating the user with the validated data
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        // Creating a token assigned to the user
        $token = $user->createToken('backend-test-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        // Returning the response with the corrected status
        return response($response, 201);
    }

    public function login(Request $request) {
        // Validating the request
        // When passing confirmed to validator, password_confirm is required too
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string' 
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
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

    public function logout(Request $request) {
        // Deleting the authenticate user tokens
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
