<?php

namespace  App\Domains\Person\Http\Controllers;

use App\Domains\Person\Services\PersonService;
use App\Support\Http\Controllers\Controller;
use App\Units\Events\MessageEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    /**
     * @var PersonService
     */
    private $service;

    public function __construct(PersonService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *      tags={"People"},
     *      description="Exibir uma lista de registros.",
     *      path="/people",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="type",
     *          description="Tipo de pessoa (0 user; 1 cliente)",
     *          required=true,
     *          in="query",
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     */
    public function getItems(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ], $this->messages());
        if ($validator->fails()) {
            $response = MessageEvent::dispatch([
                "statusCode" => 400,
                "action" => "Get",
                "error" => $validator->errors()
            ]);
            return $response[0];
        }
        return $this->service->getItems($request->all());
    }

    /**
     * @OA\Get(
     *      tags={"People"},
     *      description="Exibir um registro especificado no banco de dados.",
     *      path="/people/item/{id}",
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
     * @OA\Get(
     *      tags={"People"},
     *      description="Exibir um registro especificado no banco de dados.",
     *      path="/people/account/{number}",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="number",
     *          description="Numero da conta",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
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
     * @param  int $number
     * @return \Illuminate\Support\Collection, \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function getItemByAccount(string $number)
    {
        return $this->service->getItemByAccount($number);
    }

    /**
     * @OA\Post(
     *      tags={"People"},
     *      description="Armazenar um registro no banco de dados.",
     *      path="/people/create",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="type", type="integer"),
     *              @OA\Property(property="person", type="boolean"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="nickname", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="reason_social", type="string"),
     *              @OA\Property(property="cpf_cnpj", type="string"),
     *              @OA\Property(property="date_birth", type="string"),
     *              @OA\Property(property="gender", type="integer"),
     *              @OA\Property(property="mobile_phone", type="array", @OA\Items(@OA\Property(property="number", type="string"))),
     *              @OA\Property(property="phone", type="array", @OA\Items(@OA\Property(property="number", type="string"))),
     *              @OA\Property(property="whatsapp", type="string"),
     *              @OA\Property(property="address",
     *                  @OA\Property(property="cep",type="string"),
     *                  @OA\Property(property="street",type="string"),
     *                  @OA\Property(property="number",type="string"),
     *                  @OA\Property(property="complement",type="string"),
     *                  @OA\Property(property="district",type="string"),
     *                  @OA\Property(property="city",type="string"),
     *                  @OA\Property(property="uf",type="string"),
     *              ),
     *              @OA\Property(property="role_id", type="integer"),
     *              @OA\Property(property="note", type="string"),
     *              @OA\Property(property="state_register", type="string"),
     *              @OA\Property(property="town_register", type="string"),
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
            'type' => 'required',
            'person' => 'required',
            'name' => 'required',
            'email' => 'required|email'
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
     *      tags={"People"},
     *      description="Atualizar o registro especificado no banco de dados.",
     *      path="/people/update",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="type", type="integer"),
     *              @OA\Property(property="person", type="boolean"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="reason_social", type="string"),
     *              @OA\Property(property="cpf_cnpj", type="string"),
     *              @OA\Property(property="date_birth", type="string"),
     *              @OA\Property(property="gender", type="integer"),
     *              @OA\Property(property="mobile_phone", type="array", @OA\Items(@OA\Property(property="number", type="string"))),
     *              @OA\Property(property="phone", type="array", @OA\Items(@OA\Property(property="number", type="string"))),
     *              @OA\Property(property="whatsapp", type="string"),
     *              @OA\Property(property="address",
     *                  @OA\Property(property="cep",type="string"),
     *                  @OA\Property(property="street",type="string"),
     *                  @OA\Property(property="number",type="string"),
     *                  @OA\Property(property="complement",type="string"),
     *                  @OA\Property(property="district",type="string"),
     *                  @OA\Property(property="city",type="string"),
     *                  @OA\Property(property="uf",type="string"),
     *              ),
     *              @OA\Property(property="role_id", type="integer"),
     *              @OA\Property(property="note", type="string"),
     *              @OA\Property(property="state_register", type="string"),
     *              @OA\Property(property="town_register", type="string"),
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
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], $this->messages());
        if ($validator->fails()) {
            $response = MessageEvent::dispatch([
                "statusCode" => 400,
                "action" => "Update",
                "error" => $validator->errors()
            ]);
            return $response[0];
        }
        return $this->service->update($request->all());
    }

    /**
     * @OA\Delete(
     *      tags={"People"},
     *      description="Deletar registros especificado no banco de dados.",
     *      path="/people/delete",
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
