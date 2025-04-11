<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Investment;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InvestmentRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class EntryInvestmentController extends AbstractController
{
    private EntityManagerInterface $entityManager;    
    private InvestmentRepository $investmentRepository;   

    public function __construct(InvestmentRepository $investmentRepository, EntityManagerInterface $entityManager)
    {
        $this->investmentRepository = $investmentRepository;
        $this->entityManager = $entityManager;
    }   

    #[Route('/api/investment/{investmentId}', methods: 'GET')]
    public function viewInvestment($investmentId)
    {
        $investmentId = (int) $investmentId; 
        
        $investment = $this->getInvestmentById($investmentId);        
        
        if (!$investment) {
            return new JsonResponse([
                'message' => 'Investimento não encontrado!',
                'database_url' => $_ENV['DATABASE_URL']
            ], 404);
        }
        
        $investmentDetails = $this->calculateInvestmentDetails($investment);
        
        return new JsonResponse([
            'message' => 'Investimento encontrado!',
            'owner_name' => $investment->getOwner()->getName(),
            'value' => $investment->getValue(),
            'investment_details' => $investmentDetails
        ]);
    }

    private function getInvestmentById(int $investmentId)
    {
        return $this->investmentRepository->find($investmentId);
    }

    private function calculateInvestmentDetails(Investment $investment)
    {
        $currentDate = new DateTime();
        $startDate = $investment->getCreatedAt();

        $daysInvested = $startDate->diff($currentDate)->days;
        $annualInterestRate = 0.052;

        $earnedInterest = $investment->getValue() * (pow(1 + $annualInterestRate, $daysInvested / 365) - 1);
        $expectedBalance = $investment->getValue() + $earnedInterest;

        return [
            'id' => $investment->getId(),
            'name' => $investment->getOwner()->getName(),
            'amount' => number_format($investment->getValue(), 2, '.', ''),
            'earnings' => number_format($earnedInterest, 2, '.', ''),
            'expected_balance' => number_format($expectedBalance, 2, '.', ''),
            'days_invested' => $daysInvested,
        ];
    }

    #[Route('/api/db-info', methods: 'GET')]
    public function showDbInfo()
    {
        $response = [
            'database_url' => $_ENV['DATABASE_URL']
        ];
        
        try {
            $conn = $this->entityManager->getConnection();
            $response['connection_status'] = 'Conexão estabelecida com sucesso!';
            
            $result = $conn->executeQuery('SELECT COUNT(*) FROM investment')->fetchOne();
            $response['total_investments'] = $result;
        } catch (\Exception $e) {
            $response['connection_status'] = 'Erro ao conectar';
            $response['error'] = $e->getMessage();
            return new JsonResponse($response, 500);
        }
        
        return new JsonResponse($response);
    }

    #[Route('/api/test', methods: 'GET')]
    public function testApi()
    {
        $info = [
            'status' => 'ok',
            'php_version' => phpversion(),
            'database_url' => $_ENV['DATABASE_URL'],
            'symfony_env' => $_ENV['APP_ENV']
        ];        
        
        try {
            $connection = $this->entityManager->getConnection();
            $info['database_connected'] = true;
            
            try {
                $stmt = $connection->prepare("SELECT COUNT(*) FROM person");
                $result = $stmt->executeQuery();
                $info['person_count'] = $result->fetchOne();
            } catch (\Exception $e) {
                $info['person_table_error'] = $e->getMessage();
            }
            
            try {
                $stmt = $connection->prepare("SELECT COUNT(*) FROM investment");
                $result = $stmt->executeQuery();
                $info['investment_count'] = $result->fetchOne();
            } catch (\Exception $e) {
                $info['investment_table_error'] = $e->getMessage();
            }
        } catch (\Exception $e) {
            $info['database_connected'] = false;
            $info['database_error'] = $e->getMessage();
        }
        
        return new JsonResponse($info);
    }
}
