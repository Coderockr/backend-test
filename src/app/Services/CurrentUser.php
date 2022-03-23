<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class CurrentUser {
    protected $id;
    
    protected $jwt = "";

    public function Login($username, $password) {
        Log::debug("CurrentUser->Login->username: $username");
        if ($username == "mocked" && $password == "with mock data") { //For tests
            $this->id = -1;
            return true;
        }
        $users = \App\Models\AdminUser::where("user", $username)->get();
        foreach ($users as $user) {
            Log::debug("CurrentUser->Login->User found, checking password...");
            if(password_verify($password, $user->password)){
                Log::debug("CurrentUser->Login->Password is correct...");
                $this->id = $user->id;
                return true;
            }
        }
        return false;
    }

    public function RestoreSessionByToken($token) {
        // TODO: Needs to implement a server side hash verification
        // HOW TO: Need to add a "hash" into JWT
        //         and check if user id, hash matches
        //         on the database, into personal_access_tokens table
        // But just if you need more security @_@, 
        // because JWT is a secure method by the way
        // just don't store any sensitive data into JWT
        // and anything will be fine (y)

        try {
            // If the user change the JWT, will throw a error
            $data = \App\Services\JWT::decode($token);
            $this->id = $data->id;
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