<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserRegistrationController extends AbstractController
{
    #[Route('/user/registration', name: 'app_user_registration', methods: ['POST'])]
	public function create(
		Request $request,
		UserPasswordHasherInterface $passwordhasher,
		UserRepository $userRepository
	): JsonResponse
    {
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
		]);
    }
}
