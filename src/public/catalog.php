<?php
echo "test";
$pdo =new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
print_r($products);
require_once './catalog_page.php';
//
//session_start();
//
//if (isset($_SESSION['userId'])) {
//    $pdo = new PDO('pgsql:host=db; port=5432;dbname=mydb', 'valera', 'qwerty');
//    //если пользователь найден, выдаем каталог
//    $stmt = $pdo->query('SELECT * FROM products');
//    $products = $stmt->fetchAll();
//    require_once './catalog_page.php';
//} else {
//    header("Location: /login_form.php");
//}