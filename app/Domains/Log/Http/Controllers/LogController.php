<?php

namespace App\Domains\Log\Http\Controllers;

use App\Support\Http\Controllers\Controller;
use App\Domains\Log\Services\LogService;

class LogController extends Controller
{
    /**
     * @var LogService
     */
    private $service;

    public function __construct(LogService $service)
    {
        $this->service = $service;
    }

     /**
     * @OA\Get(
     *      tags={"Logs"},
     *      description="Exibir uma lista de registros.",
     *      path="/logs",
     *      security={{"bearerAuth":{}}},
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
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     */
    public function getItems()
    {
        return $this->service->getItems();
    }

}