<?php

namespace App\Controller\Api\Investimento;

use App\Service\BuscarPropietarioService;
use App\Service\ListaDeInvestimentoDeUmaPessoaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ListaDeInvestimentoDeUmaPessoaController extends AbstractController
{
    #[Route("/api/lista/{propietario}",methods:["GET"])]
    public function __invoke(
        int $propietario,
        ListaDeInvestimentoDeUmaPessoaService $listaDeInvestimentoDeUmaPessoaService,
        BuscarPropietarioService $buscar
    ):JsonResponse
    {
        $buscar->BuscarPropietarioOufalhar(id:$propietario);
        try{
            return $this->json($listaDeInvestimentoDeUmaPessoaService->execute(id:$propietario),200);
        }catch(Throwable $e){
            return $this->json([
                "error" => "Erro ao buscar investimentos"
            ],500);
        }
    }
}