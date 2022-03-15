<?php

namespace App\Controllers\Api;

use CodeIgniter\Controller;

class Products extends Controller
{
    protected $helpers = ['apoio'];
    protected $request;
    protected $model;
    public function __construct()
    {
        $this->model = new \App\Models\Products();

    }

    public function index()
    {
        responseJson([
            'success' => true,
            'body' => $this->model->get()->getResult()
        ]);
    }

}
