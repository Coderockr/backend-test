<?php

namespace Database;

final class Transaction
{

    private static $conn;


    private function __construct()
    {
    }


    public static function open()
    {
        if (empty(self::$conn)) 
        {
            self::$conn = Connection::open();
            self::$conn->beginTransaction();
            return true;
        }
        return false;
    }


    public static function get()
    {
        return self::$conn;
    }


    public static function close()
    {
        if (self::$conn) 
        {
            self::$conn->commit();
            self::$conn = null;
        }
    }


    public static function rollback()
    {
        if (self::$conn) 
        {
            self::$conn->rollback();
            self::$conn = null;
        }
    }
}