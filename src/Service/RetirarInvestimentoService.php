<?php 

namespace App\Service;

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

    public function execute(int $id, array $dados): array
    {
        $investimento = $this->buscarInvestimentoService->buscarInvestimentoOuFalhar(id:$id);
        $valorQueOusuarioQuerRetirar = (float) $dados["retirada"];

        $dataQueInvestiu = $investimento->getDataCriacao();
        $dataAtual = $this->pegarDataAtualService->obterDataAtual();
        
        $aliquota = $this->calcularAliquota->CalcularAliquota(dataQueInvestiu: $dataQueInvestiu);

        $dadosGanhos = $this->calcularGanhoService->calcularGanho(
            dataQueInvestiu: $dataQueInvestiu,
            valorIniciaInvestimento: $investimento->getValor(),
            valorAliquota: $aliquota
        );

        $impostoSobreInvestimento = $dadosGanhos["impostoSobreInvestimento"];

        if($impostoSobreInvestimento <= 0){
            $valorAcerRetiradoPeloPropietario = $valorQueOusuarioQuerRetirar;

            $sobraDoSaque =  $investimento->getValor() - $valorAcerRetiradoPeloPropietario; 

            $investimento->setDataRetirada($dataAtual);
            $investimento->setValor($sobraDoSaque);
            $this->investimentoRepository->salvar($investimento);

            return  [
                "saque" => $valorAcerRetiradoPeloPropietario,
                "imposto" => 0,
            ];
        }
       
        $valorAcerRetiradoPeloPropietario = $valorQueOusuarioQuerRetirar - $impostoSobreInvestimento;
        $sobraDoSaque =  $investimento->getValor() - $valorAcerRetiradoPeloPropietario; 

        $investimento->setDataRetirada($dataAtual);
        $investimento->setValor($sobraDoSaque);
        $this->investimentoRepository->salvar($investimento);

        return  [
            "saque" => $valorAcerRetiradoPeloPropietario,
            "imposto" => $impostoSobreInvestimento,
        ];
    }
}