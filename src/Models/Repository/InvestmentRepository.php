<?php

namespace App\Models\Repository;

use App\Models\Entities\Client;
use App\Models\Entities\Investment;
use Doctrine\ORM\EntityRepository;

class InvestmentRepository extends EntityRepository
{
    public function save(Investment $entity): Investment
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function getByClient(Client $client, int $offset, int $limit = 2)
    {
        $params[':client'] = $client->getId();
        return $this->getEntityManager()->createQuery(
            "SELECT i FROM  App\Models\Entities\Investment AS i
                JOIN i.client AS c
                WHERE c.id = :client
                ORDER BY i.id ASC")
            ->setMaxResults($limit)
            ->setFirstResult($offset * $limit)
            ->execute($params);
    }


}