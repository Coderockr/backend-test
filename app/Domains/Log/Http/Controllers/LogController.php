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
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getItems()
    {
        return $this->service->getItems();
    }

}