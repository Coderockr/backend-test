<?php

namespace App\Entity;

use App\Repository\PropietarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use JsonSerializable;

#[ORM\Entity(repositoryClass: PropietarioRepository::class)]
class Propietario 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;
   
    /**
     * @var Collection<int, Investimento>
     */
    #[ORM\OneToMany(targetEntity: Investimento::class, mappedBy: 'propietario')]
    #[JoinColumn(nullable:true)]
    private Collection $investimentos;

    public function __construct
    (
        #[ORM\Column(length: 255)]
        private string $nome
    )
    {
        $this->investimentos = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return Collection<int, Investimento>
    */
    public function getInvestimentos(): Collection
    {
        return $this->investimentos;
    }

  
}
