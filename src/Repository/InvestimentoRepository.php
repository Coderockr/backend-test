<?php

namespace App\Repository;

use App\Entity\Investimento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Investimento>
 */
class InvestimentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investimento::class);
    }

    public function salvar(Investimento $investimento):Investimento
    {
        $this->getEntityManager()->persist($investimento);
        $this->getEntityManager()->flush();
        return $investimento;
    }

}
