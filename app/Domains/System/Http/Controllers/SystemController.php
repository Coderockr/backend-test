<?php

namespace  App\Domains\System\Http\Controllers;

use App\Domains\System\Services\SystemService;
use App\Support\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemController extends Controller
{

    /**
     * @var SystemService
     */
    private $service;

    public function __construct(SystemService $service)
    {
        $this->service = $service;
    }

    /**
     * Exibir uma lista de registros.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     */
    public function getQueue(Request $request)
    {
        return $this->service->getQueue($request->all());
    }
    
}