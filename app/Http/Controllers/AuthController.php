<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
}
