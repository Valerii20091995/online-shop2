<?php
class Cart
{
  public function getByUserProducts(int $userId):array|false
  {
      $pdo = new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
      $pdo->prepare('SELECT * FROM user_products WHERE user_id = ' . $userId);
      $userProducts = $stmt->fetchAll();
      return $userProducts;
  }
  public function getByProduct(int $productId):array|false
  {
      $pdo = new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
      $stmt = $pdo->query("SELECT * FROM products WHERE id = $productId");
      $stmt->execute(['product_id' => $productId]);
      $product = $stmt->fetch();
      return $product;
  }
}