<?php

namespace App\Domains\Person\Repositories;

use App\Domains\Person\Models\Role;
use App\Support\Database\Repository\Repository;

class RoleRepository extends Repository
{
    protected $modelClass = Role::class;

    /**
     * Exibir uma lista de registros.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getItems()
    { 
        return $this->newQuery()
                ->with('groupRole')
                ->get();
    }

    /**
     * Buscar um registro especifico no banco de dados.
     * 
     * @param int $id
     * @return object|null
     */
    public function getItem(int $id)
    { 
        return $this->newQuery()
                ->where('id', $id)
                ->first();
    }

}