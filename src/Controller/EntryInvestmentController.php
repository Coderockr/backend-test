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
       
        echo "Buscando investimento ID: " . $investmentId . "<br>";
        
        $investment = $this->getInvestmentById($investmentId);        
        
        if (!$investment) {
            echo "Investimento não encontrado!<br>";
            echo "URL do banco: " . $_ENV['DATABASE_URL'] . "<br>";
            return new JsonResponse(['error' => 'Investment not found'], 404);
        }
        
        echo "Investimento encontrado!<br>";
        echo "Nome do proprietário: " . $investment->getOwner()->getName() . "<br>";
        echo "Valor: " . $investment->getValue() . "<br>";
        
        $investmentDetails = $this->calculateInvestmentDetails($investment);
        
        echo "<pre>";
        print_r($investmentDetails);
        echo "</pre>";
        
        exit; 
        
        return new JsonResponse(['investment_details' => $investmentDetails]);
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
            'amount' => $investment->getValue(),
            'earnings' => $earnedInterest,
            'expected_balance' => $expectedBalance,
            'days_invested' => $daysInvested,
        ];
    }

    #[Route('/api/db-info', methods: 'GET')]
    public function showDbInfo()
    {
        echo "Configuração do Banco de Dados:<br>";
        echo "DATABASE_URL: " . $_ENV['DATABASE_URL'] . "<br>";
        
        try {
            $conn = $this->entityManager->getConnection();
            echo "Conexão estabelecida com sucesso!<br>";            
            
            $result = $conn->executeQuery('SELECT COUNT(*) FROM investment')->fetchOne();
            echo "Total de investimentos: " . $result . "<br>";
            
            return new Response("Verificação concluída", 200);
        } catch (\Exception $e) {
            echo "Erro ao conectar: " . $e->getMessage() . "<br>";
            return new Response("Erro de conexão", 500);
        }
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
