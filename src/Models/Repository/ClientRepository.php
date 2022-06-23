<?php

namespace App\Models\Repository;

use App\Models\Entities\Client;
use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository
{
    public function save(Client $entity): Client
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }


}