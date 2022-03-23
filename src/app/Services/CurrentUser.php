<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class CurrentUser {
    protected $id;
    
    protected $jwt = "";

    public function Login($user, $password) {
        $this->id = 441;
        return ($user == "mocked" && $password == "with mock data");
    }

    public function RestoreSessionByToken($token) {
        try {
            $data = \App\Services\JWT::decode($token);
            //Log::debug("RestoreSessionByToken->data: " . json_encode($data));
            $this->id = $data->id;
            //Log::debug("RestoreSessionByToken->User ID: " . $this->id);
            return true;
        }catch(Exception $e){
            return false;
        }
        return false;
    }
    
    public function GetToken() {
        return \App\Services\JWT::encode(['id' => $this->id]);
    }

    public function GetId() {
        return $this->id;
    }
}