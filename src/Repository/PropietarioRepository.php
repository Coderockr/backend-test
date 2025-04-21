<?php

namespace App\Repository;

use App\Controller\Exceptions\NaoEncontrouPropietarioException;
use App\Entity\Investimento;
use App\Entity\Propietario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Propietario>
 */
class PropietarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Propietario::class);
    }

    
    /**
     * lista de investimentos de um propietario
     * @return Investimento<array>
     * @return Propietario
    */
    public function listaDeInvestimentoPorUsuario(int $id): array
    {
        $query = $this->createQueryBuilder('p') 
            ->innerJoin('p.investimentos', 'i') 
            ->addSelect('i') 
            ->where('p.id = :id') 
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;

        if(empty($query)){
            throw new NaoEncontrouPropietarioException();
        }

        return $query;
    }
}
