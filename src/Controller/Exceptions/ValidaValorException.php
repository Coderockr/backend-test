<?php 

namespace App\Controller\Exceptions;

use Exception;

class ValidaValorException extends Exception
{
    public function __construct()
    {
        parent::__construct("Valor incorreto para investimento");
    }
}