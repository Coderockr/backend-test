<?php 

namespace App\Controller\Exceptions;

use Exception;

class DadosVaziosException extends Exception
{
    public function __construct()
    {
        parent::__construct("Dados vazios,por favor preencha os campos");
    }
}