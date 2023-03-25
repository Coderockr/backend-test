<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InvestAPIException extends Exception
{
    protected $message;
    protected $code;
    protected $data;

    public function __construct(string $message = "", int $code = 0, array $data = [])
    {
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'statuscode' => $this->code,
            'data' => $this->data,
            'message' => $this->message,
        ], $this->code);
    }
}
