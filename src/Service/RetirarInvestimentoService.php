<?php 

namespace App\Service;

use App\Entity\Investimento;
use App\Repository\InvestimentoRepository;
use App\Service\CalcularGanhoService as ServiceCalcularGanhoService;

class RetirarInvestimentoService
{
    public function __construct(
        private InvestimentoRepository $investimentoRepository,
        private BuscarInvestimentoService $buscarInvestimentoService,
        private CalcularAliquotaService $calcularAliquota,
        private ServiceCalcularGanhoService $calcularGanhoService,
        private PegarDataAtualService $pegarDataAtualService
    )
    {
    }

    public function execute(Investimento $investimento): array
    {
        $investimento = $investimento;;

        $dataQueInvestiu = $investimento->getDataCriacao();
        $dataAtual = $this->pegarDataAtualService->obterDataAtual();
        
        $aliquota = $this->calcularAliquota->CalcularAliquota(dataQueInvestiu: $dataQueInvestiu);

        $dadosCalculo = $this->calcularGanhoService->calcularGanho(
            dataQueInvestiu: $dataQueInvestiu,
            valorIniciaInvestimento: $investimento->getValor(),
            valorAliquota: $aliquota
        );


        if($dadosCalculo["impostoSobreInvestimento"] <= 0){
            $investimento->setDataRetirada($dataAtual);
            $investimento->setValor(0);
            $this->investimentoRepository->salvar($investimento);

            return  [
                "saque" => $dadosCalculo["valorAserRetiradoPeloPropietario"],
                "imposto" => 0,
            ];
        }
        $investimento->setDataRetirada($dataAtual);
        $investimento->setValor(0);
        $this->investimentoRepository->salvar($investimento);

        return  [
            "saque" => $dadosCalculo["valorAserRetiradoPeloPropietario"],
            "imposto" => $dadosCalculo["impostoSobreInvestimento"],
        ];
    }
}