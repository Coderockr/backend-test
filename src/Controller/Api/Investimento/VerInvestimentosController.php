<?php 

namespace App\Controller\Api\Investimento;

use App\Repository\InvestimentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class VerInvestimentosController extends AbstractController
{
    #[Route("/api/verInvestimentos",methods:["GET"])]
    public function __invoke(InvestimentoRepository $i):JsonResponse
    {
        try{
            $i->findAll();
            
            dd($i->getPropietario());


        }catch(Throwable $e){
            return $this->json([
                "error" => "erro ao ver investimentos"
            ],500);
        }
    }
    
}