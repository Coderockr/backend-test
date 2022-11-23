<?php

namespace App\Controller;

use App\Entity\Investment;
use App\Entity\User;
use App\Repository\InvestmentRepository;
use App\Service\InvestmentCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

		if ($investment->dateOfWithdrawal()) {
			$balanceExpect = $investmentCalculator->calculateGains($investment,$investment->dateOfWithdrawal()) + $investment->value();
		}

		return $this->json([
			...$investment->jsonSerialize(),
			'balance_expect' => round($balanceExpect, 2),
		], Response::HTTP_OK);
	}

	#[Route('/withdrawal/{id}', name: 'app_investment_preview_withdrawl', methods: ['POST'])]
	public function previewWithdrawal(
		Uuid $id,
		Request $request,
		ValidatorInterface $validator,
		InvestmentRepository $repository,
		InvestmentCalculator $investmentCalculator,
		EntityManagerInterface $em,
	): JsonResponse
	{
		$input = $request->toArray();
		$errors = [];

		$constraint = new Assert\Collection([
			'date' => new Assert\Optional(new Assert\Date()),
		]);

		$violations = $validator->validate($input, $constraint);

		foreach($violations as $violation) {
			$errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
		}

		if (count($errors) > 0) {
			return $this->json([
				'errors' => $errors
			], Response::HTTP_BAD_REQUEST);
		}

		if (array_key_exists('date', $input) && strtotime($input['date']) > strtotime(date('Y-m-d'))) {
			return $this->json([
				'error' => 'Data invalida.'
			], Response::HTTP_BAD_REQUEST);
		}

		$investment = $repository->find($id);

		if (!$investment) {
			return $this->json(['error' => 'Investimento nao encontrado'], Response::HTTP_BAD_REQUEST);
		}

		if ($investment->dateOfWithdrawal()) {
			return $this->json([
				'error' => 'Este Investimento ja teve uma retirada'
			], Response::HTTP_BAD_REQUEST);
		}

		$date = new \DateTimeImmutable('now');

		if (array_key_exists('date', $input)) {
			$date = new \DateTimeImmutable($input['date']);
		}

		$value = $investmentCalculator->calculateGains($investment, $date) + $investment->value();
		$withdrawalValue = $value - $investmentCalculator->calculateTaxes($investment, $date);

		$investment
			->setWithdrawal(round($withdrawalValue, 2))
			->setDateOfWithdrawal($date);

		$em->flush();

		return $this->json([
			'success' => [
				'message' => 'Retida com sucesso.',
				'withdrawal_value' => round($withdrawalValue, 2)
			]
		]);
	}

	#[Route('/list', name: 'app_investment_list', methods: ['GET'])]
	public function list(
		Request $request,
		InvestmentRepository $repository,
	): JsonResponse
	{
		$page = $request->query->get('page') ?? 0;
		/** @var User $user */
		$user = $this->getUser();
		$investments = $repository->findByUser($user, ($page === 0 ? 0 : $page - 1));

		return $this->json([
			'results' => $investments,
			'page' => $page
		]);
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
