<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (isset($_SESSION['userId'])) {
    $pdo = new DataBase('pgsql:host=db; port=5432;dbname=mydb', 'valera', 'qwerty');
    $stmt = $pdo->query('SELECT * FROM users WHERE id = ' . $_SESSION['userId']);
    $user= $stmt->fetch();
    require_once './Views/profile_form.php';
} else {
    header("Location: /login");
}



