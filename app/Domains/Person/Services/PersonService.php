<?php

namespace App\Domains\Person\Services;

use App\Domains\Person\Repositories\AccountRepository;
use App\Units\Events\MessageEvent;
use App\Units\Events\LogEvent;
use App\Domains\Person\Repositories\PersonRepository;
use App\Domains\Person\Repositories\PhoneRepository;
use App\Domains\System\Repositories\AddressRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class PersonService
{
    /**
     * @var PersonRepository
     */
    private $repo;

    public function __construct(PersonRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Exibir uma lista de registros.
     *
     * @param  array $data
     * @return \Illuminate\Http\Response
     */
    public function getItems(array $data)
    {
        return $this->repo->getItems($data);
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
     * Exibir um registro especificado no banco de dados.
     *
     * @param string $cpf_cnpj
     * @return \Illuminate\Support\Collection|(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder)[]
     */
    public function getItemByCpfCnpj(string $cpf_cnpj)
    {
        return $this->repo->getItemByCpfCnpj($cpf_cnpj);
    }

    /**
     * Exibir um registro especificado no banco de dados.
     *
     * @param string $account
     * @return \Illuminate\Support\Collection|(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder)[]
     */
    public function getItemByAccount(string $account)
    {
        return $this->repo->getItemByAccount($account);
    }

    /**
     * Exibir um registro especificado no banco de dados.
     *
     * @param string $key
     * @param $value
     * @return \Illuminate\Support\Collection|(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder)[]
     */
    public function getUser(string $key, $value)
    {
        return $this->repo->getUser($key, $value);
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
            if(isset($data["cpf_cnpj"])){
                $data["cpf_cnpj"] = preg_replace('/[^0-9]/', '', $data["cpf_cnpj"]);
                $data["person"] = strlen($data["cpf_cnpj"]) === 11 ? false : true;
            }
            if(isset($data['password']) && $data['password']){
                $data['password'] = Hash::make($data['password']);
            }
            if(isset($data['address'])){
                $address = new AddressRepository();
                $address = $address->create($data['address']);
                $data['address_id'] = $address->id;
            }
            $person = $this->repo->create($data);
            $id = $person->id;
            if(isset($data['mobile_phone'])){
                foreach ($data['mobile_phone'] as $item){
                    if($item['number']){
                        $mobile_phone = new PhoneRepository();
                        $data_mobile_phone['type'] = 1;
                        $data_mobile_phone['number'] = $item['number'];
                        $data_mobile_phone['person_id'] = $id;
                        $mobile_phone->create($data_mobile_phone);
                    }
                }
            }
            if(isset($data['phone'])){
                foreach ($data['phone'] as $item){
                    if($item['number']){
                        $phone = new PhoneRepository();
                        $data_phone['type'] = 0;
                        $data_phone['number'] = $item['number'];
                        $data_phone['person_id'] = $id;
                        $phone->create($data_phone);
                    }
                }
            }
            if(isset($data['whatsapp'])){
                $whatsapp = new PhoneRepository();
                $data_whatsapp['type'] = 2;
                $data_whatsapp['number'] = $data['whatsapp'];
                $data_whatsapp['person_id'] = $id;
                $whatsapp->create($data_whatsapp);
            }
            $repo = new AccountRepository();
            $account['number'] = substr_replace(str_pad($id , 5 , '0' , STR_PAD_LEFT),'-', 4, 0);
            $account['person_id'] = $id;
            $repo->create($account);
            LogEvent::dispatch(["event"=>
                [
                    "statusCode" => 201,
                    "action" => "Create",
                    "table" => "public.people",
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
                "data" => $person
            ]);
            return $response[0];
        } catch (\Throwable $th) {
            DB::rollBack();
            $response =  MessageEvent::dispatch([
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
            $person = $this->repo->findOne($data['id']);
            if(isset($data['password']) && $data['password']){
                $data['password'] = Hash::make($data['password']); 
            }else{
                $data['password'] = $person->password;
            }
            if(isset($data['address'])){
                $repo = new AddressRepository();
                if($data['address_id']){
                    $address = $repo->findOne($data['address_id']);
                    $address = $repo->update($address, $data['address']);
                }else{
                    $address = $repo->create($data['address']);
                }
                $data['address_id'] = $address->id;
            }
            $person = $this->repo->update($person, $data);
            $id = $person->id;
            if(isset($data['mobile_phone'])){
                foreach ($data['mobile_phone'] as $item){
                    if($item['number']){
                        $mobile_phone = new PhoneRepository();
                        $data_mobile_phone['type'] = 1;
                        $data_mobile_phone['number'] = $item['number'];
                        $data_mobile_phone['person_id'] = $id;
                        if(isset($item['id'])){
                            $mobile_phone = $mobile_phone->findOne($item['id']);
                            $mobile_phone = $mobile_phone->update($mobile_phone, $data_mobile_phone);
                        }else {
                            $mobile_phone = $mobile_phone->create($data_mobile_phone);
                        }
                    }
                }
            }
            if(isset($data['phone'])){
                foreach ($data['phone'] as $item){
                    if($item['number']){
                        $phone = new PhoneRepository();
                        $data_phone['type'] = 0;
                        $data_phone['number'] = $item['number'];
                        $data_phone['person_id'] = $id;
                        if(isset($item['id'])){
                            $phone = $phone->findOne($item['id']);
                            $phone = $phone->update($phone, $data_phone);
                        }else {
                            $phone = $phone->create($data_phone);
                        }
                    }
                }
            }
            if(isset($data['whatsapp'])){
                foreach ($data['whatsapp'] as $item){
                    if($item['number']){
                        $whatsapp = new PhoneRepository();
                        $data_whatsapp['type'] = 0;
                        $data_whatsapp['number'] = $item['number'];
                        $data_whatsapp['person_id'] = $id;
                        if(isset($item['id'])){
                            $whatsapp = $whatsapp->findOne($item['id']);
                            $whatsapp = $whatsapp->update($whatsapp, $data_whatsapp);
                        }else {
                            $whatsapp = $whatsapp->create($data_whatsapp);
                        }
                    }
                }
            }
            LogEvent::dispatch(["event"=>
                [
                    "statusCode" => 200,
                    "action" => "Update",
                    "table" => "public.people",
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
                "data" => $person
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
