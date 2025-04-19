<?php 

namespace App\Controller\Exceptions;

use Exception;

class NaoEncontrouPropietarioException extends Exception
{
    public function __construct()
    {
        return parent::__construct("Falha aou buscar propietario");
    }
}