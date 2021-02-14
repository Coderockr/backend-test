<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $this->validate($request, [
            'name' => ['required', 'min:4'],
            'email' => ['required', 'unique:users', 'email'],
            'bio' => ['nullable'],
            'location' => ['nullable'],
            'password' => ['required', 'min:8', 'confirmed'],
            'picture' => ['nullable', 'file', 'image', 'dimensions:min_width=250,min_height=250', 'max:2048'],
        ]);

        $user = (new User)->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'bio' => $data['bio'],
            'location' => $data['location'],
            'password' => bcrypt($data['password']),
        ]);

        if ($file = $request->file('picture')) {
            $user->picture_file = Storage::disk('public')->putFile('profile', $file);
        }

        $user->save();
        $token = $user->createToken('CoderockrToken')->accessToken;

        return response()->json(array_merge(
            $user->toArray(),
            ['token' => $token]
        ), 201);
    }

    public function login(Request $request)
    {
        $input = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        if (auth()->attempt($input)) {
            $user = auth()->user();
            $token = $user->createToken('CoderockrToken')->accessToken;

            return response()->json(array_merge(
                $user->toArray(),
                ['token' => $token]
            ));
        }

        return response()->json(null, 401);
    }
}
