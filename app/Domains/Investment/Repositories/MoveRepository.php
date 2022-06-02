<?php

namespace App\Domains\Investment\Repositories;

use App\Domains\Investment\Models\Move;
use App\Support\Database\Repository\Repository;
use Illuminate\Support\Collection;

class MoveRepository extends Repository
{

    protected $modelClass = Move::class;

    /**
     * Exibir uma lista de registros.
     * 
     * @param  Array  $filter
     * @return \Illuminate\Http\Response
     */
    public function getItems(array $filter, Collection $accounts = null)
    { 
        $query = $this->newQuery();
        if($accounts){
            $accounts->map(function($item){
                return $item->id;
            });
            $query->whereIn("account_id", $accounts);
        }
        if(isset($filter['type'])){
            $type = $filter['type'];
            if(is_array($type)){
                $type = implode(',', $type);
                $query->whereRaw("type in (${type})");
            }else{
                $query->where("type", $type);
            }
        }
        $query->orderBy('id');
        if(isset($filter['page'])){
            return $query->paginate($this->paginate);
        }
        return $query->get();
    }

}