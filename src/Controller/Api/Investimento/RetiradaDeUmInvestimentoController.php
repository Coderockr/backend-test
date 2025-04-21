<?php 

namespace App\Controller\Api\Investimento;

use App\Service\BuscarInvestimentoService;
use App\Service\RetirarInvestimentoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class RetiradaDeUmInvestimentoController extends AbstractController
{
    #[Route("/api/retiraInvestimento/{investimento}",methods:["GET"])]
    public function __invoke(
        int $investimento,
        RetirarInvestimentoService $retirarInvestimentoService,
        BuscarInvestimentoService $buscarInvestimentoService,
    ):JsonResponse
    {
        $investimento = $buscarInvestimentoService->buscarInvestimentoOuFalhar(id:$investimento);
        try{
            return $this->json($retirarInvestimentoService->execute(investimento:$investimento),200);
        }catch(Throwable $e){
            return $this->json([
                "error" => "erro ao retirar investimento",
                $e->getMessage()
            ],500);
        }
    }
}




