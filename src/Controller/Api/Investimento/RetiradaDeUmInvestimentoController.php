<?php 

namespace App\Controller\Api\Investimento;

use App\Service\RetirarInvestimentoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class RetiradaDeUmInvestimentoController extends AbstractController
{
    #[Route("/api/retiraInvestimento/{investimento}",methods:["GET"])]
    public function __invoke(int $investimento,Request $request, RetirarInvestimentoService $retirarInvestimentoService):JsonResponse
    {
        $dados  = json_decode($request->getContent(),true);
        try{
            return $this->json($retirarInvestimentoService->execute(id:$investimento,dados:$dados));
        }catch(Throwable $e){
            return $this->json([
                "error" => "erro ao retirar investimento",
                $e->getMessage()
            ],500);
        }
    }
    
}


//❌ Não pode ser uma data anterior à data de criação do investimento.

//❌ Não pode ser uma data futura.


//Saques parciais não são permitidos. O saque sempre envolve todo o valor do investimento atualizado com rendimentos.