<?php

namespace Database;

use PDO;
use Exception;

final class Connection
{
    private function __construct()
    {
    }

    public static function open()
    {
        $dbHost = (null !== getenv('DB_HOST')) ? getenv('DB_HOST') : NULL;
        $dbPort = (null !== getenv('DB_PORT')) ? getenv('DB_PORT') : NULL;
        $dbName = (null !== getenv('DB_DATABASE')) ? getenv('DB_DATABASE') : NULL;
        $dbUser = (null !== getenv('DB_USERNAME')) ? getenv('DB_USERNAME') : NULL;
        $dbPass = (null !== getenv('DB_PASSWORD')) ? getenv('DB_PASSWORD') : NULL;

        $conn = new PDO("mysql:host={$dbHost}; port={$dbPort}; dbname={$dbName}", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}