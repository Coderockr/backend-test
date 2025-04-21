<?php 

namespace App\Controller\Api\Investimento;

use App\Service\VerInvestimentoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class VerInvestimentosController extends AbstractController
{
    #[Route("/api/verInvestimentos/{investimento}",methods:["GET"])]
    public function __invoke(int $investimento,VerInvestimentoService $verInvestimentoService):JsonResponse
    {
        try{
          return $this->json($verInvestimentoService->execute($investimento),200); 
        }catch(Throwable $e){
            return $this->json([
                "error" => "erro ao ver investimentos"
            ],500);
        }
    }
    
}