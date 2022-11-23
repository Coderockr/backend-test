<?php

namespace App\Controller;

use App\Entity\Investment;
use App\Repository\InvestmentRepository;
use App\Service\InvestmentCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

#[Route('/api/investment')]
class InvestmentController extends AbstractController
{
    #[Route('/create', name: 'app_investment_create', methods: ['POST'])]
	public function create(
		Request $request,
		InvestmentRepository $repository,
	): JsonResponse
	{
		$data = $request->toArray();
		$errors = $this->validateInput($data);

		if (count($errors) > 0) {
			return $this->json([
				'errors' => $errors
			], Response::HTTP_BAD_REQUEST);
		}

		$investment = new Investment($this->getUser(), $data['value'], new \DateTimeImmutable($data['created_at']));

		$repository->save($investment, true);

        return $this->json([
            'success' => 'Investimento criado com sucesso.',
        ]);
    }

	#[Route('/show/{id}', name: 'app_investment_show', methods: ['GET'])]
	public function show(
		Uuid $id,
		InvestmentRepository $repository,
		InvestmentCalculator $investmentCalculator,
	): JsonResponse
	{
		$investment = $repository->find($id);

		if (!$investment) {
			return $this->json(['error' => 'Investimento nao encontrado'], Response::HTTP_BAD_REQUEST);
		}

		$balanceExpect = $investmentCalculator->calculateGains($investment) + $investment->value();

		if ($investment->dateOfWithdrawl()) {
			$balanceExpect = $investmentCalculator->calculateGains($investment,$investment->dateOfWithdrawl()) + $investment->value();
		}

		return $this->json([
			...$investment->jsonSerialize(),
			'balance_expect' => round($balanceExpect, 2),
		], Response::HTTP_OK);
	}
	
	private function validateInput(array $input): array
	{
		$validator = Validation::createValidator();
		$errors = [];

		$constraint = new Assert\Collection([
			'value' => new Assert\Positive(),
			'created_at' => new Assert\Date(),
		]);

		$violations = $validator->validate($input, $constraint);

		foreach($violations as $violation) {
			$errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
		}

		return $errors;
	}
}
