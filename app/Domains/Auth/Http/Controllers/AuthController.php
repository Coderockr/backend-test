<?php

namespace App\Domains\Auth\Http\Controllers;

use App\Domains\Auth\Services\AuthService;
use App\Support\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AuthController extends Controller
{

    private $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *      tags={"Auth"},
     *      description="Autenticar usuário",
     *      path="/auth/login",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        return $this->service->authenticate($request->all());
    }

    /**
     * Verificar o usuário autenticado
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        return $this->service->getAuthenticatedUser();
    }

}
