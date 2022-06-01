<?php

namespace App\Domains\Person\Services;

use App\Domains\Person\Repositories\RoleRepository;
use App\Units\Events\LogEvent;
use App\Units\Events\MessageEvent;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleService
{
    /**
     * @var RoleRepository
     */
    private $repo;

    public function __construct(RoleRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Exibir uma lista de registros.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getItems()
    {
        return $this->repo->getItems();
    }

    /**
     * Exibir um registro especificado no banco de dados.
     *
     * @param int $id
     * @return \Illuminate\Support\Collection|(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder)[]
     */
    public function getItem(int $id)
    {
        return $this->repo->getItem($id);
    }

    /**
     * Armazenar um registro no banco de dados.
     *
     * @param  Array  $data
     * @return \Illuminate\Http\Response
     */
    public function create(array $data)
    {
        $user = JWTAuth::user();
        DB::beginTransaction();
        try {
            $role = $this->repo->create($data);
            LogEvent::dispatch(["event"=>
                [
                    "statusCode" => 201,
                    "action" => "Create",
                    "table" => "public.roles",
                    "user" => [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                    ]
                ]
            ]);
            DB::commit();
            $response = MessageEvent::dispatch([
                "statusCode" => 201,
                "action" => "Create",
                "data" => $role
            ]);
            return $response[0];
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = MessageEvent::dispatch([
                "statusCode" => 400,
                "action" => "Create",
                "error" => isset($th->errorInfo) && count($th->errorInfo) ? $th->errorInfo[count($th->errorInfo) -1] : (string) $th
            ]);
            return $response[0];
        }
    }

    /**
     * Atualizar o registro especificado no banco de dados.
     *
     * @param  Array  $data
     * @return \Illuminate\Http\Response
     */
    public function update(array $data)
    {      
        $user = JWTAuth::user();
        DB::beginTransaction();
        try { 
            $role = $this->repo->findOne($data['id']);
            $this->repo->update($role, $data);
            LogEvent::dispatch(["event"=>
                [
                    "statusCode" => 200,
                    "action" => "Update",
                    "table" => "public.roles",
                    "user" => [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                    ]
                ]
            ]);
            DB::commit();
            $response = MessageEvent::dispatch([
                "statusCode" => 200,
                "action" => "Update",
                "data" => $role
            ]);
            return $response[0];
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = MessageEvent::dispatch([
                "statusCode" => 400,
                "action" => "Update",
                "error" => isset($th->errorInfo) && count($th->errorInfo) ? $th->errorInfo[count($th->errorInfo) -1] : (string) $th
            ]);
            return $response[0];
        }
    }

    /**
     * Deletar registros especificado no banco de dados.
     *
     * @param  Array  $ids
     * @return \Illuminate\Http\Response
     */
    public function delete(array $ids)
    {
        return $this->repo->delete($ids);
    }
    
}
