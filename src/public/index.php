<?php

//try {
//    $pdo = new PDO("pgsql:host=db;port=5432;dbname=mydb;", 'valera', 'qwerty');
//    print_r($pdo);
    // Установите режим обработки ошибок PDO на исключения
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    print_r($pdo);
//} catch (PDOException $e) {
//    echo "Ошибка подключения: " . $e->getMessage();
//}


$pdo =new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
$stmt = $pdo->query("SELECT * FROM users");
$data = $stmt->fetchAll();
echo "<pre>";
print_r($data);

