<?php 

namespace App\Service;

use App\Controller\Exceptions\ValidaValorException;
use App\Entity\Investimento;
use App\Repository\InvestimentoRepository;

class CriarInvestimentoService
{
    public function __construct(
        private InvestimentoRepository $investimentoRepository,
        private BuscarPropietarioService $buscarPropietarioService
    )
    {
    }

    public function execute(array $dados,int $id): Investimento
    {
        $propietario = $this->buscarPropietarioService->BuscarPropietarioOufalhar(id:$id);

        if($dados['valor'] <= 0){
            throw new ValidaValorException();
        }

        $investimento = new Investimento(
            $propietario,
            $dados['valor']
        );

        return $this->investimentoRepository->salvar($investimento);
    }
}