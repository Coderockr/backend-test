<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'username' => 'required|email',
                'password' => 'required'
            ]);

            $response = Http::asForm()->post(config('services.passport.endpoint'), [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.secret'),
                'username' => $request->username,
                'password' => $request->password,
                'scope' => '*'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'You have been successfully logged.',
                'data' => $response->json()
            ]);
        } catch (ClientException $exception) {
            Log::info('Login: ' . $exception->getMessage());

            if ($exception->getCode() === 400 || $exception->getCode() === 401) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your login details are incorrect. Please try again.',
                    'data' => null
                ], 401);
            }

            return response()->json([
                'success' => false,
                'message' => 'Internal server error.',
                'data' => $exception->getMessage()
            ], $exception->getCode());
        }
    }
}
