<?php

namespace App\Service;

use DateTimeImmutable;

class PegarDataAtualService
{
    public function obterDataAtual(): DateTimeImmutable
    {
        return new DateTimeImmutable();   
    }
}