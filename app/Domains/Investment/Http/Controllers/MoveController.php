<?php

namespace  App\Domains\Investment\Http\Controllers;

use App\Support\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Investment\Services\MoveService;
use App\Units\Events\MessageEvent;
use Illuminate\Support\Facades\Validator;

class MoveController extends Controller
{
    /**
     * @var MoveService
     */
    private $service;

    public function __construct(MoveService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *      tags={"Investments"},
     *      description="Exibir uma lista de registros.",
     *      path="/investments/moves",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getItems(Request $request)
    {
        return $this->service->getItems($request->all());
    }

    /**
     * @OA\Get(
     *      tags={"Investments"},
     *      description="Exibir um registro especificado no banco de dados.",
     *      path="/investments/moves/item/{id}",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Código do registro",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * @param  int $id
     * @return \Illuminate\Support\Collection, \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function getItem(int $id)
    { 
        return $this->service->getItem($id);
    }

    /**
     * @OA\Post(
     *      tags={"Investments"},
     *      description="Armazenar um registro no banco de dados.",
     *      path="/investments/moves/create",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="type", type="integer"),
     *              @OA\Property(property="value", type="string"),
     *              @OA\Property(property="registered_at", type="string"),
     *              @OA\Property(property="account_id", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "type" => [
                "required",
                "integer",
                "min:0",
                "max:1"
            ],
            "value" => [
                "required_if:type,0",
                "numeric",
                "min:0.1"
            ],
            "registered_at" => [
                "required",
                "date",
                "date_format:Y-m-d",
                "before:tomorrow"
            ],
            "account_id" => "required_if:type,0"
        ], $this->messages());
        if ($validator->fails()) {
            $response = MessageEvent::dispatch([
                "statusCode" => 400,
                "action" => "Create",
                "error" => $validator->errors()
            ]);
            return $response[0];
        }
        return $this->service->create($request->all());
    }

    /**
     * @OA\Put(
     *      tags={"Investments"},
     *      description="Atualizar o registro especificado no banco de dados.",
     *      path="/investments/moves/update",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="type", type="integer"),
     *              @OA\Property(property="number", type="string"),
     *              @OA\Property(property="person_id", type="integer")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request)
    {
        return $this->service->update($request->all());
    }

    /**
     * @OA\Delete(
     *      tags={"Investments"},
     *      description="Deletar registros especificado no banco de dados.",
     *      path="/investments/moves/delete",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="ids", type="array", @OA\Items()),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required',
        ], $this->messages());
        if ($validator->fails()) {
            $response = MessageEvent::dispatch([
                "statusCode" => 400,
                "action" => "Delete",
                "error" => $validator->errors()
            ]);
            return $response[0];
        }
        return $this->service->delete($request->ids);
    }

}
