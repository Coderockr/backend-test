<?php

namespace App\Domains\Investment\Services;

use App\Domains\Investment\Repositories\MoveRepository;
use App\Domains\Person\Repositories\AccountRepository;
use App\Units\Events\LogEvent;
use App\Units\Events\MessageEvent;
use App\Units\Jobs\Gain;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class MoveService
{
    /**
     * @var MoveRepository
     */
    private $repo;

    public function __construct(MoveRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Exibir uma lista de registros.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getItems(array $data)
    {
        $user = JWTAuth::user();
        $repo = new AccountRepository();
        $accounts = $repo->getItemByPersonId($user->id);
        $accounts = $accounts->map(function($item){
            return $item->id;
        })->all();
        return $this->repo->getItems($data, $accounts);
    }

    /**
     * Exibir um registro especificado no banco de dados.
     *
     * @param int $id
     * @return \Illuminate\Support\Collection|(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder)[]
     */
    public function getItem(int $id)
    {
        return $this->repo->findOne($id);
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
            if($data["type"] === 0){
                $move = $this->repo->create($data);
            }else{
                dd("saque");
                $move = $this->repo->create($data);
            }
            LogEvent::dispatch(["event"=>
                [
                    "statusCode" => 201,
                    "action" => "Create",
                    "table" => "public.moves",
                    "user" => [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                    ]
                ]
            ]);
            DB::commit();
            Gain::dispatch($move->account_id, $move->id)->onQueue('gain')->delay(now()->addMinutes(1));
            // Gain::dispatch($move->account_id, $move->id)->onQueue('gain')->delay(now()->addMonth());
            $response = MessageEvent::dispatch([
                "statusCode" => 201,
                "action" => "Create",
                "data" => $move
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
     * Armazenar um registro no banco de dados.
     *
     * @param  Array  $data
     * @return \Illuminate\Http\Response
     */
    public function gain(int $account_id, int $move_id)
    {
        DB::beginTransaction();
        try {
            $data["type"] = 2;
            $data["account_id"] = $account_id;
            $data["move_id"] = $move_id;
            $moves = $this->repo->getItems(
                ["type" => [0, 2]], 
                [$account_id]
            );
            $moves = $moves->filter(function($item) use ($move_id){
                return $item->id === $move_id || $item->move_id === $move_id;
            })->values();
            $current_value = $moves->reduce(function ($carry, $item) {
                return $carry + $item->value;
            });
            $data["value"] = $current_value*0.0052;
            $move = $this->repo->create($data);
            LogEvent::dispatch(["event"=>
                [
                    "statusCode" => 201,
                    "action" => "Create",
                    "table" => "public.moves"
                ]
            ]);
            DB::commit();
            Gain::dispatch($account_id, $move_id)->onQueue('gain')->delay(now()->addMinutes(1));
            // Gain::dispatch($account_id, $move_id)->onQueue('gain')->delay(now()->addMonth(1));
            $response = MessageEvent::dispatch([
                "statusCode" => 201,
                "action" => "Create",
                "data" => $move
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
            $move = $this->repo->findOne($data['id']);
            $this->repo->update($move, $data);
            LogEvent::dispatch(["event"=>
                [
                    "statusCode" => 200,
                    "action" => "Update",
                    "table" => "public.moves",
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
                "data" => $move
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
        $ids = implode(',', $ids);
        return $this->repo->newQuery()
            ->whereRaw("id in (${ids})")
            ->delete();
    }
    
}
