<?php
class DataBase
{
    private static $PDO = null;
    public static function getPDO():PDO
    {
        if (self::$PDO === null) {
            self::$PDO = new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
        }
        return self::$PDO;
    }

}