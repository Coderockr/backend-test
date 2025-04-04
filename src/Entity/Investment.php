<?php

namespace App\Entity;

use App\Repository\InvestmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvestmentRepository::class)]
class Investment implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'investments')]
    #[ORM\JoinColumn(nullable: false)]
    private Person $owner;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private float $value;

    #[ORM\OneToMany(mappedBy: 'investment', targetEntity: Movement::class, cascade: ["persist"])]
    private Collection $movements;
    
    #[ORM\Column]
    private float $earnings = 0.0;
    
    public function __construct() 
    {
        $this->movements = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOwner(): Person
    {
        return $this->owner;
    }

    public function setOwner(Person $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getMovements(): Collection
    {
        return $this->movements;
    }

    public function getBalance(): float
    {
        $amount = $this->value;        
        $balance = $this->getMovements()->reduce(fn(float $carry, Movement $movement) => $carry += $movement->getValue(), 0);

        return $amount + $balance;
    }

    public function addMovement(Movement $movement): static
    {
        if (!$this->movements->contains($movement)) {
            $this->movements->add($movement);
            $movement->setInvestment($this);
        }

        return $this;
    }       
    
    public function getEarnings(): float
    {
        return $this->earnings;
    }

    public function setEarnings(float $earnings): static
    {
        $this->earnings = $earnings;

        return $this;
    }
    
    public function jsonSerialize(): mixed
    {
        return[
            'id' => $this->getId(),
            'createdAt' => $this->getCreatedAt(),
            'value' => $this->getValue(),
            'owner' => $this->getOwner()
        ];         
    }
}
