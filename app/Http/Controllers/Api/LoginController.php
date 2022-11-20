<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $client = new Client();
            $request->validate([
                'username' => 'required|email',
                'password' => 'required'
            ]);

            $response = $client->post(config('services.passport.endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.secret'),
                    'username' => $request->username,
                    'password' => $request->password
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'You have been successfully logged.',
                'data' => json_decode($response->getBody()->getContents())
            ]);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 400) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request. Please enter a valid username and password.',
                    'data' => null,
                ], $exception->getCode());
            } else if ($exception->getCode() === 401) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your login details are incorrect. Please try again.',
                    'data' => null
                ], $exception->getCode());
            }

            return response()->json([
                'success' => false,
                'message' => 'Internal error.',
                'data' => $exception->getMessage()
            ], $exception->getCode());
        }
    }
}
