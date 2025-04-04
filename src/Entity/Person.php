<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Investment::class, cascade:["ALL"])]
    private Collection $investments;

    public function __construct()
    {
        $this->investments = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Investment>
     */
    public function getInvestments(): Collection
    {
        return $this->investments;
    }

    public function addInvestment(Investment $investment): static
    {
        if (!$this->investments->contains($investment)) {
            $this->investments->add($investment);
            $investment->setOwner($this);
        }

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return[
            'id' => $this->getId(),
            'name' => $this->getName(),
            /*'investments' => $this->getInvestments()->toArray()*/
        ];         
    }
}
