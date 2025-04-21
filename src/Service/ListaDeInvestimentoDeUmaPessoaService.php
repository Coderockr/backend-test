<?php 

namespace App\Service;

use App\Repository\PropietarioRepository;

class ListaDeInvestimentoDeUmaPessoaService
{
    public function __construct(
        private PropietarioRepository $propietarioRepository,
    )
    {
    }

    /**
     * lista de investimentos de um propietario
     * @return Investimento<array>
     * @return Propietario
    */
    public function execute(int $id): array
    {
        $listaDeInvestimentoPorUsuario = $this->propietarioRepository->listaDeInvestimentoPorUsuario(id:$id);

        $resultado = [];
        foreach($listaDeInvestimentoPorUsuario as $lista){
            $listaPropietario = $lista->getInvestimentos();
            $listaDeInvestimentos = [];
            $total = 0;

            foreach($listaPropietario as $valor){
                $total += $valor->getValor();

                $listaDeInvestimentos[] = [
                    "id" => $valor->getId(),
                    "valor" => $valor->getValor()
                ];
            }

            $resultado[] = [
                "propietario" => $lista->getNome(),
                "investimentos" => $listaDeInvestimentos,
                "total" => $total
            ];
        }
        return $resultado;
    }
}