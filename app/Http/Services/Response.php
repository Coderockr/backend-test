<?php

namespace App\Http\Services;

use Symfony\Component\HttpFoundation\Response as a;

trait Response
{
    public function responseSuccess($message = 'success', $data = null)
    {
        return response([
            'message'   =>  $message,
            'error'     =>  false,
            'data'      =>  $data
        ], 200);
    }

    public function responseError($status = 400, $message = 'error', $data = null)
    {
        return response([
            'message'   =>  $message,
            'error'     =>  true,
            'data'      =>  $data
        ], $status);
    }
}
