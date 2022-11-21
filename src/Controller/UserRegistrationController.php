<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class UserRegistrationController extends AbstractController
{
    #[Route('/user/registration', name: 'app_user_registration', methods: ['POST'])]
	public function create(
		Request $request,
		UserPasswordHasherInterface $passwordhasher,
		UserRepository $userRepository,
	): JsonResponse
    {
		$errors = $this->validateInput($request->toArray());

		if (count($errors) > 0) {
			return $this->json([
				'errors' => $errors
			], Response::HTTP_BAD_REQUEST);
		}

		$user = (new User())
			->setName($request->toArray()['name'])
			->setEmail($request->toArray()['email']);

		$hashedPassword = $passwordhasher->hashPassword(
			$user,
			$request->toArray()['password']
		);
		$user->setPassword($hashedPassword);

		$userRepository->save($user, true);

		return $this->json([
			'success' => 'Usuario criado com sucesso'
		], Response::HTTP_OK);
    }

	private function validateInput(array $input): array
	{
		$validator = Validation::createValidator();
		$errors = [];

		$constraint = new Assert\Collection([
			'name' => new Assert\NotBlank(),
			'email' => new Assert\Email(),
			'password' => new Assert\Length(['min' => 6, 'max' => 18]),
		]);

		$violations = $validator->validate($input, $constraint);
		// dd($violations);

		foreach($violations as $violation) {
			$errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
		}

		return $errors;
	}
}
