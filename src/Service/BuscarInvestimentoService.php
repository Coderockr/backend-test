<?php 

namespace App\Service;

use App\Controller\Exceptions\NaoEncontrouInvestimentoException;
use App\Entity\Investimento;
use App\Repository\InvestimentoRepository;

class BuscarInvestimentoService
{
    public function __construct(
        private InvestimentoRepository $investimentoRepository
    )
    {
    }

    public function buscarInvestimentoOuFalhar(int $id): Investimento
    {
        $investimento = $this->investimentoRepository->find($id);

        if(null === $investimento){
            return throw new NaoEncontrouInvestimentoException();
        }
        return $investimento;
    }
}