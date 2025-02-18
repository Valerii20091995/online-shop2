<?php
require_once '../Model/DataBase.php';
class Product
{

    private PDO $PDO;
    public function __construct()
    {
        $this->PDO = DataBase::getPDO();
    }
    public function getByProduct($productId):array|false
    {
        $stmt = $this->PDO->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $result = $stmt->fetch();
        return $result;
    }
    public function getByCatalog(int $userId):array|false
    {
        $stmt = $this->PDO->query('SELECT * FROM products');
        $result = $stmt->fetchAll();
        return $result;

    }


}