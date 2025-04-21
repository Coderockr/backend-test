<?php 

namespace App\Service;

use App\Repository\InvestimentoRepository;

class VerInvestimentoService
{
    public function __construct
    (
        private InvestimentoRepository $investimentoRepository,
        private BuscarInvestimentoService $buscarInvestimentoService,
        private PegarDataAtualService $pegarDataAtualService
    )
    {
    }

    public function execute(int $id):array
    {
        $investimento = $this->buscarInvestimentoService->buscarInvestimentoOuFalhar(id:$id);

        $mesQueInvestiu = $investimento->getDataCriacao();
        $mesOuDiaAtual = $this->pegarDataAtualService->obterDataAtual(); 
        
        $diferenca = $mesQueInvestiu->diff($mesOuDiaAtual);
        $messes = ($diferenca->y * 12) + $diferenca->m;
        
        $saldoAtual = $investimento->getValor() * (1 + 0.0052) ** $messes;
        $numeroFormatado = number_format($saldoAtual, 2, ',', '.');
        
        return [
            "valorInicial" =>$investimento->getValor(),
            "saldoAtual" => $numeroFormatado
        ];
    }
}