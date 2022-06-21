<?php

namespace App\Models\Repository;

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

    public function getByCompany(Company $company, ?int $id = null)
    {
        $where = '';
        $params[':company'] = $company->getId();
        if ((int)$id > 0) {
            $params[':id'] = $id;
            $where = ' AND u.id = :id';
        }
        $query = $this->getEntityManager()->createQuery(
            "SELECT u FROM  App\Models\Entities\Client as u             
                JOIN u.company as c
                WHERE c.id = :company {$where}
                ORDER BY u.name ASC");
        $query->execute($params);
        return $query->getResult();
    }


}