<?php

namespace  App\Domains\Person\Http\Controllers;

use App\Support\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Person\Services\RoleService;
use App\Units\Events\MessageEvent;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * @var RoleService
     */
    private $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

   /**
     * @OA\Get(
     *      tags={"People"},
     *      description="Exibir uma lista de registros.",
     *      path="/people/roles",
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
    public function getItems()
    { 
        return $this->service->getItems();
    }

    /**
     * @OA\Get(
     *      tags={"People"},
     *      description="Exibir um registro especificado no banco de dados.",
     *      path="/people/roles/item/{id}",
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
     *      tags={"People"},
     *      description="Armazenar um registro no banco de dados.",
     *      path="/people/roles/create",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
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
        return $this->service->create($request->all());
    }

    /**
     * @OA\Put(
     *      tags={"People"},
     *      description="Atualizar o registro especificado no banco de dados.",
     *      path="/people/roles/update",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="name", type="string"),
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
        return $this->repo->update($request->all());
    }

    /**
     * @OA\Delete(
     *      tags={"People"},
     *      description="Deletar registros especificado no banco de dados.",
     *      path="/people/roles/delete",
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
