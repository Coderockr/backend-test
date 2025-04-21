<?php 

namespace App\Controller\Api\Investimento;

use App\Controller\Exceptions\DadosVaziosException;
use App\Service\CriarInvestimentoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class CriarInvestimentoController extends AbstractController
{
    #[Route("/api/criar/{propietario}", methods:['POST'])]
    public function __invoke(
        int $propietario,
        Request $request,
        CriarInvestimentoService $criarInvestimentoService
    ):JsonResponse
    {
        $dados = json_decode($request->getContent(),true);
        try{
            if(empty($dados)){
                throw new DadosVaziosException();
            }

            $criarInvestimentoService->execute(dados:$dados,id:$propietario);

            return $this->json(["mensage" => "sucesso ao investir"],200);
        }catch(Throwable $e){
            return $this->json([
                "error" => "Erro ao criar investimento",
                'message' => $e->getMessage()
            ],500);
        }
    }
}
