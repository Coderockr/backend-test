<?php

namespace App\Controller\Api\Investimento;

use App\Repository\InvestimentoRepository;
use App\Repository\PropietarioRepository;
use App\Service\ListaDeInvestimentoDeUmaPessoaService;
use Doctrine\Common\Collections\Expr\Value;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ListaDeInvestimentoDeUmaPessoaController extends AbstractController
{
    #[Route("/api/lista/{propietario}",methods:["GET"])]
    public function __invoke(int $propietario,ListaDeInvestimentoDeUmaPessoaService $listaDeInvestimentoDeUmaPessoaService):JsonResponse
    {
        try{
            return $this->json($listaDeInvestimentoDeUmaPessoaService->execute(id:$propietario));
        }catch(Throwable $e){
            return $this->json([
                "error" => "Erro ao buscar investimentos"
            ]);
        }
    }
}