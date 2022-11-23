<?php

namespace App\Entity;

use App\Repository\InvestmentRepository;
use Symfony\Component\Uid\Uuid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: InvestmentRepository::class)]
class Investment implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
	#[ORM\GeneratedValue(strategy: 'CUSTOM')]
	#[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column]
    private float $value;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateOfWithdrawl = null;

	public function __construct(
		User $user,
		float $value,
		\DateTimeImmutable $createdAt
	)
	{
		$this->user = $user;
		$this->value = $value;
		$this->createdAt = $createdAt;
	}

	public function value(): float
	{
		return $this->value;
	}

	public function createdAt(): \DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function dateOfWithdrawl(): \DateTimeImmutable
	{
		return $this->dateOfWithdrawl;
	}

	public function jsonSerialize(): mixed
	{
		return [
			'id' => $this->id,
			'initial_value' => $this->value,
			'created_at' => $this->createdAt->format('Y-m-d'),
			'date_of_withdrawl' => $this->dateOfWithdrawl?->format('Y-m-d')
		];
	}
}
