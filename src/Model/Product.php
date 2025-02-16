<?php
class Product
{
    public function getByCatalog(int $userId):array|false
    {
        $pdo = new PDO('pgsql:host=db; port=5432;dbname=mydb', 'valera', 'qwerty');
        $stmt = $pdo->query('SELECT * FROM products');
        $result = $stmt->fetchAll();
        return $result;

    }
    public function getByUserProducts(int $userId, int $productId):array|false
    {
        $pdo = new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
        $stmt = $pdo->prepare("SELECT *FROM user_products WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();
        return $data;

    }
    public function addProductByUser(int $userId, int $productId, int $amount):array|false
    {
        $pdo = new PDO('pgsql:host=db; port=5432;dbname=mydb', 'valera', 'qwerty');
        $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
    }
    public function updateProductByUser(int $amount, int $productId, int $userId):array|false
    {
        $pdo = new PDO('pgsql:host=db; port=5432;dbname=mydb', 'valera', 'qwerty');
        $stmt = $pdo->prepare("UPDATE user_products SET amount = :amount WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['amount' => $amount, 'productId' => $productId, 'userId' => $userId]);
        $stmt = $pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $result = $stmt->fetch();
        return $result;


    }
    public function getByProduct($productId):array|false
    {
        $pdo = new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $result = $stmt->fetch();
        return $result;
    }

}