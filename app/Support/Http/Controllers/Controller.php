<?php

namespace App\Support\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Server(url=API_HOST)
 * @OA\Info(
 *  title=APP_NAME, 
 *  description="Documentação",
 *  version="0.1",
 *  @OA\Contact(
 *   email="diones.souza.calca@gmail.com"
 *  ),
 *  @OA\License(
 *   name="Nginx",
 *   url="http://nginx.org/LICENSE"
 *  )
 * )
 *  
 * @OA\SecurityScheme(
 *  type="http",
 *  scheme="bearer",
 *  securityScheme="bearerAuth"
 * )
 * 
*/

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required'  => 'O campo :attribute é obrigatório.',
            'required_if'  => 'O campo :attribute é obrigatório quando :other é :value.',
            'email' => 'O campo :attribute é inválido.'
        ];
    }
}
