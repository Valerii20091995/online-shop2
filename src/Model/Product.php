<?php

class Product extends Model
{
    public function getByProduct($productId):array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $result = $stmt->fetch();
        return $result;
    }
    public function getByCatalog(int $userId):array|false
    {
        $stmt = $this->pdo->query('SELECT * FROM products');
        $result = $stmt->fetchAll();
        return $result;

    }


}