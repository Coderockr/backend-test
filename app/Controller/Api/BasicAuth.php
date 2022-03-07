<?php

namespace App\Controller\Api;

use App\Model\Entity\Investor;

class BasicAuth
{
    private static function getBasicAuthInvestor()
    {
        if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']))
        {
            return false;
        }

        $investor = Investor::getInvestorByLogin($_SERVER['PHP_AUTH_USER']);
        if(!$investor instanceof Investor)
        {
            return false;
        }

        return password_verify($_SERVER['PHP_AUTH_PW'], $investor->password) ? $investor : false;
    }
    
    
    public static function basicAuth($request)
    {
        if($investor = self::getBasicAuthInvestor())
        {
            $request->investor = $investor;
            return true;
        }

        throw new \Exception("User or password invalid", 403);
    }
}