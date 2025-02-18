<?php
require_once '../Model/DataBase.php';
class User_Product
{
    private PDO $PDO;
    public function __construct()
    {
        $this->PDO = DataBase::getPDO();
    }
    public function getByUserProducts(int $userId, int $productId):array|false
    {
        $stmt = $this->PDO->prepare("SELECT *FROM user_products WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        return $stmt->fetch();


    }
    public function addProductByUser(int $userId, int $productId, int $amount):void
    {
        $stmt = $this->PDO->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
    }
    public function updateProductByUser(int $amount, int $productId, int $userId):array|false
    {
        $stmt = $this->PDO->prepare("UPDATE user_products SET amount = :amount WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['amount' => $amount, 'productId' => $productId, 'userId' => $userId]);
        $stmt = $this->PDO->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $result = $stmt->fetch();
        return $result;


    }
    public function getByUser_Product(int $userId):array|false
    {
        $stmt = $this->PDO->prepare("SELECT * FROM user_products WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

}