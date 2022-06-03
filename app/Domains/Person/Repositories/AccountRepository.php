<?php

namespace App\Domains\Person\Repositories;

use App\Domains\Person\Models\Account;
use App\Support\Database\Repository\Repository;

class AccountRepository extends Repository
{
    protected $modelClass = Account::class;

    /**
     * Exibir uma lista de registros.
     * 
     * @param int $person_id
     * @return \Illuminate\Support\Collection
     */
    public function getItemByPersonId(string $person_id)
    { 
        return $this->newQuery()
                ->where('person_id', $person_id)
                ->get();
    }

    /**
     * Buscar um registro especifico no banco de dados.
     * 
     * @param int $number
     * @return \Illuminate\Support\Collection
     */
    public function getItemByNumber(string $number)
    { 
        return $this->newQuery()
                ->where('number', $number)
                ->first();
    }
    
}