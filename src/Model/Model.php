<?php
namespace Model;
use PDO;

abstract class Model
{
    protected PDO $pdo;
    public function __construct()
    {
        $this->pdo = new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
    }
    abstract protected function getTableName(): string;

}