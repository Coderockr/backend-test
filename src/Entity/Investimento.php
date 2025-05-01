<?php

namespace App\Entity;

use App\Repository\InvestimentoRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvestimentoRepository::class)]
class Investimento 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private \DateTimeImmutable $dataCriacao;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dataRetirada = null;
   
    public function __construct(
        #[ORM\ManyToOne(inversedBy: 'investimentos')]
        #[ORM\JoinColumn(nullable: false)]
        private Propietario $propietario,

        #[ORM\Column]
        private float $valor,
    )
    {
        $this->dataCriacao = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDataCriacao(): \DateTimeImmutable
    {
        return $this->dataCriacao;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function setValor(float $valor): float
    {
        return $this->valor = $valor;
    }

    public function getPropietario(): Propietario
    {
        return $this->propietario;
    }

    public function getDataRetirada(): ?\DateTimeImmutable
    {
        return $this->dataRetirada;
    }

    public function setDataRetirada(?\DateTimeImmutable $dataRetirada): static
    {
        $this->dataRetirada = $dataRetirada;

        return $this;
    }
}
