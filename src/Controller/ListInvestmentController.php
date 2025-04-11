<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Investment;
use App\Repository\InvestmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Routing\Attribute\Route;

class ListInvestmentController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private InvestmentRepository $investmentRepository;

    public function __construct(InvestmentRepository $investmentRepository, EntityManagerInterface $entityManager)
    {
        $this->investmentRepository = $investmentRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/investments/listInvestments', methods: 'GET')]
    public function listInvestments(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('perPage', 10);

        $repository = $this->entityManager->getRepository(Investment::class);

        $query = $repository->createQueryBuilder('i')
            ->orderBy('i.id', 'DESC')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $formattedInvestments = [];

        foreach ($paginator as $investment) {
            $formattedInvestments[] = $this->formatInvestment($investment);
        }

        return new JsonResponse(['investments' => $formattedInvestments, 'total' => count($paginator)]);
    }

    private function formatInvestment(Investment $investment)
    {
        return [
            'id' => $investment->getId(),
            'value' => $investment->getValue(),
            'createdAt' => $investment->getCreatedAt()->format('Y-m-d H:i:s'),            
        ];
    }
}

