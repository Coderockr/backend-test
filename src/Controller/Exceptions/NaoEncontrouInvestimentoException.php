<?php 

namespace App\Controller\Exceptions;

use Exception;

class NaoEncontrouInvestimentoException extends Exception
{
    public function __construct()
    {
        return parent::__construct("Falha ao buscar investimento");
    }
}