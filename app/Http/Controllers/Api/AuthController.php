<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends ApiController
{

    /**
     *  Handles with the registration of new users as well as their validation and creation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:175',
            'email' => 'required|string|email|max:175|unique:users',
            'password' => 'required|string|min:6', // confirmed
            'city' => 'required|string|min:2|max:175',
            'state' => 'required|string|min:2|max:2',
            'biography' => 'string|max:255',
            'avatar' => 'mimes:jpeg,jpg,png|max:1024', // image
        ]);

        if ($validator->fails()) {
            return $this->responseUnprocessable($validator->errors()->toArray());
        }

        try {
            $user = $this->create($request);
            return $this->responseSuccess('Registered successfully.');
        } catch (Exception $e) {
            dd($e);
            return $this->responseServerError('Registration error.');
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \App\Models\User
     */
    protected function create(Request $request)
    {
        $data = $request->all();
        unset($data['avatar']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'city' => $data['city'],
            'state' => mb_strtoupper($data['state'], 'UTF-8'),
            'biography' => array_key_exists('biography', $data) && $data['biography'] ? $data['biography'] : null
        ]);

        // get the avatar file
        $file = $request->file('avatar');
        if ($file) { // if exists
            // get the file extension
            $extension = $file->getClientOriginalExtension();

            // generate the storage file name
            $filename = mb_strtolower(Str::random(10) . "-" . $user->id . "." . $extension, 'UTF-8');

            // storage the file in disk
            Storage::disk('users_avatars')->put($filename, File::get($file));

            // set the filename in the model
            $user->update(['avatar' => $filename]);
        }

        return $user;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->responseUnauthorized();
        }

        // Get the user data.
        $user = auth('api')->user();

        return $this->respondWithToken($user, $token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return $this->responseSuccess('Successfully logged out.');
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($user, $token)
    {
        return response()->json([
            'status' => 200,
            'message' => 'Authorized.',
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth('api')->factory()->getTTL() * 60
            // 'user' => $user
        ], 200);
    }
}
