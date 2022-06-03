<?php

namespace App\Domains\Auth\Services;

use App\Domains\Person\Repositories\PersonRepository;
use App\Units\Events\MessageEvent;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthService
{
    private $repo;

    public function __construct(PersonRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Autenticar usuário
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(array $data)
    {
        try {
            if(!str_contains($data["email"], "@")){
                $person = $this->repo->getUser('nickname', $data['email']);
                if($person){
                    $data["email"] = $person->email;
                }
            }
            $credentials =  $data;
            if (!$token = JWTAuth::attempt($credentials)) {
                $response = MessageEvent::dispatch([
                    "statusCode" => 400,
                    "action" => "Authenticate",
                    "error" => "Usuário ou senha inválido"
                ]);
                return $response[0];
            }
            $response = MessageEvent::dispatch([
                "statusCode" => 200,
                "action" => "Authenticate",
                "data" => [
                    'token' => compact('token')['token'],
                    'user' => $this->repo->getUser('email', $data['email'])
                ]
            ]);
            return $response[0];
        } catch (JWTException $jwt) {
            $response = MessageEvent::dispatch([
                "statusCode" => 400,
                "action" => "Authenticate",
                "error" => $jwt
            ]);
            return $response[0];
        }
    }

    /**
     * Verificar autenticação do usuário 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], 400);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], 400);
        } catch (JWTException $e) {
            return response()->json(['token_absent'], 400);
        }
        return response()->json(compact('user'));
    }

}