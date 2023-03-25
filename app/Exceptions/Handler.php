<?php

namespace App\Exceptions;

use BadMethodCallException;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
        });

        $this->renderable(function (Throwable $e, $request) {

            if ($request->is('api/*')) {

                if ($e->getPrevious() instanceof ErrorException) {
                    return response()->json([
                        'status' => 'Error',
                        'statuscode' => 500,
                        'data' => [],
                        'message' => 'Falha ao tentar buscar propriedade :' . $e->getPrevious()->getTraceAsString(),
                    ], 500);
                }

                if ($e->getPrevious() instanceof BadMethodCallException) {
                    return response()->json([
                        'status' => 'Error',
                        'statuscode' => 500,
                        'data' => [],
                        'message' => 'A chamada do método é indefinida :' . $e->getPrevious()->getTraceAsString()
                    ], 500);
                }

                if ($e->getPrevious() instanceof HttpException) {
                    return response()->json([
                        'status' => 'Error',
                        'statuscode' => 404,
                        'data' => [],
                        'message' => 'Falha ao executar requisição HTTP : ' .  $e->getPrevious(),
                    ], 500);
                }

                if ($e->getPrevious() instanceof ModelNotFoundException) {
                    return response()->json([
                        'status' => 'Error',
                        'statuscode' => 404,
                        'data' => [],
                        'message' => 'Falha ao recuperar a instância do modelo : ' . str_replace('App\\Models\\', '', $e->getPrevious()->getModel())
                    ], 404);
                }

                if ($e->getPrevious() instanceof MethodNotAllowedHttpException) {
                    return response()->json([
                        'status' => 'Error',
                        'statuscode' => 405,
                        'data' => [],
                        'message' => 'O método especificado para a solicitação é inválido'
                    ], 405);
                }

                if ($e->getPrevious() instanceof RequestException) {
                    return response()->json([
                        'status' => 'Error',
                        'statuscode' => 500,
                        'data' => [],
                        'message' => 'Falha ao tentar buscar propriedade :' . $e->getPrevious()->getTraceAsString()
                    ], 405);
                }
            }
        });
    }
}
