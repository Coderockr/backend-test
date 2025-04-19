<?php

namespace App\Entity;

use App\Repository\InvestimentoRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: InvestimentoRepository::class)]
class Investimento 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private \DateTimeImmutable $dataCriacao;
   
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

    public function getPropietario(): Propietario
    {
        return $this->propietario;
    }

   
}
