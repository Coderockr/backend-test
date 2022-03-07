<?php

namespace App\Model\Entity;

use Database\Transaction;
use Database\Repository;

use DateTime;

class Investor
{
    public $id;    
    public $login;
    public $password;
    public $firstName;
    public $lastName;
    public $genre;
    public $birthDate;
    public $cratedAt;
    public $updatedAt;


    public static function getInvestors($where = null, $order = null, $limit = null, $fields = '*')
    {
        Transaction::open();
        $result = (new Repository('investor'))->select($where, $order, $limit, $fields);
        Transaction::close();

        return $result;
    }


    public static function getInvestorByLogin($login)
    {
        return self::getInvestors('login = "'.$login.'"')->fetchObject(self::class);
    }

    
    public static function getInvestorById($id)
    {
        return self::getInvestors('id = '.$id)->fetchObject(self::class);
    }

    
    public function insert()
    {
        $this->id = (new Repository('investor'))->insert([
            'login' => $this->login,
            'password' => $this->password,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'genre' => $this->genre,
            'birthDate' => $this->birthDate,
            'createdAt' => (new DateTime())->format('Y-m-d H:i:s'),
            'updatedAt' => (new DateTime())->format('Y-m-d H:i:s')
        ]);

        return $this->id ? $this->id : false;
    }

    
    public function update()
    {
        return (new Repository('investor'))->update('id = '.$this->id, [
            'login' => $this->login,
            'password' => $this->password,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'genre' => $this->genre,
            'birthDate' => $this->birthDate,
            'createdAt' => $this->cratedAt,
            'updatedAt' => (new DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    
    public function delete()
    {
        return (new Repository('investor'))->delete('id = '.$this->id);
    }
}