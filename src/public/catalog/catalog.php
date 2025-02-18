<?php
session_start();

if (isset($_SESSION['userId'])) {
    $pdo = new DataBase('pgsql:host=db; port=5432;dbname=mydb', 'valera', 'qwerty');
    //если пользователь найден, выдаем каталог
    $stmt = $pdo->query('SELECT * FROM products');
    $products = $stmt->fetchAll();
    require_once './catalog/catalog_form.php';
} else {
    header("Location: ./login");
}