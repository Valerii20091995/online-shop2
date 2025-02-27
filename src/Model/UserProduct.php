<?php
namespace Model;
class UserProduct extends Model
{
    public function getByUserProducts(int $userId, int $productId):array|false
    {
        $stmt = $this->pdo->prepare("SELECT *FROM user_products WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        return $stmt->fetch();


    }
    public function addProductByUser(int $userId, int $productId, int $amount):void
    {
        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
    }
    public function updateProductByUser(int $amount, int $productId, int $userId):array|false
    {
        $stmt = $this->pdo->prepare("UPDATE user_products SET amount = :amount WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['amount' => $amount, 'productId' => $productId, 'userId' => $userId]);
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $result = $stmt->fetch();
        return $result;


    }
    public function getAllByUserId(int $userId):array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
    public function deleteOrder(int $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
    }

}