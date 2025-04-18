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

    public function __construct(
        #[ORM\Column(length: 255)]
        private string $propietario,

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

    public function getPropietario(): string
    {
        return $this->propietario;
    }

    public function getDataCriacao(): \DateTimeImmutable
    {
        return $this->dataCriacao;
    }

    public function getValor(): float
    {
        return $this->valor;
    }
}
