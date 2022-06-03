<?php

namespace App\Domains\Person\Repositories;

use App\Domains\Person\Models\Person;
use App\Support\Database\Repository\Repository;

class PersonRepository extends Repository
{
    protected $modelClass = Person::class;

    /**
     * Exibir uma lista de registros.
     * 
     * @param  Array  $filter
     * @return \Illuminate\Http\Response
     */
    public function getItems(array $filter)
    { 
        $query = $this->newQuery()
                ->select(
                    "id",
                    "active",
                    "person",
                    "name",
                    "nickname",
                    "photo",
                    "reason_social",
                    "cpf_cnpj",
                    "date_birth",
                    "gender",
                    "email",
                    "address_id",
                    "name_dad",
                    "name_mother",
                    "note",
                    "role_id",
                )
                ->with('address')
                ->with('role')
                ->with('phone');
        if (isset($filter['search'])){
            $search = $filter['search'];
            // atalho para buscar somente pelo id
            if ($search[0] === '/' && ctype_digit(substr($search,1))) {
                $query->where('id', intval(substr($search,1)));
            } else {
                $query->whereRaw("id || name || email ILIKE "."'%{$search}%'");
            }
        }
        $query->orderBy('id');
        if(isset($filter['page'])){
            return $query->paginate($this->paginate);
        }
        return $query->get();
    }

    /**
     * Exibir um registro especifico no banco de dados (usuario).
     * 
     * @param  string  $key
     * @param  $value
     * @return object|null
     */
    public function getUser(string $key, $value)
    {
        return $this->newQuery()
                ->select(
                    "id",
                    "name",
                    "nickname",
                    "email",
                    "cpf_cnpj"
                )
                ->where($key, $value)
                ->first();
    }

    /**
     * Exibir um registro especifico no banco de dados.
     * 
     * @param int $id
     * @return object|null
     */
    public function getItem(int $id)
    { 
        return $this->newQuery()
                ->with('role')
                ->with('address')
                ->with('phone')
                ->with('account')
                ->where('id', $id)
                ->first();
    }

    /**
     * Buscar um registro especifico no banco de dados.
     * 
     * @param string $cpf_cnpj
     * @return object|null
     */
    public function getItemByCpfCnpj(string $cpf_cnpj)
    { 
        return $this->newQuery()
                ->where('cpf_cnpj', $cpf_cnpj)
                ->first();
    }

    /**
     * Buscar um registro especifico no banco de dados.
     * 
     * @param string $account
     * @return object|null
     */
    public function getItemByAccount(string $account)
    { 
        return $this->newQuery()
                ->leftJoin('accounts','person_id','=','people.id')
                ->where('accounts.number', $account)
                ->first();
    }

}
