<?php
$errors = [];
function validate(array $date): array
{
    $errors = [];
    // проверка наличия переменных
    if (!isset($date['username'])) {
        $errors['username'] = "Поле Username обязательно для заполнения!";
    }
    if (!isset($date['password'])) {
        $errors['password'] = "Поле Password обязательно для заполнения!";
    }
    return $errors;
}

$errors = validate($_POST);

// если нет ошибок, подключаемся к БД
if (empty($errors)) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $pdo = new DataBase('pgsql:host=db; port=5432;dbname=mydb', 'valera', 'qwerty');
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $username]);
    $user = $stmt->fetch();

    if ($user === false) {
        $errors['username'] = "Логин или пароль указаны неверно!";
    } else {
        $passwordDB = $user['password'];

        if (password_verify($password, $passwordDB)) {

            //успешный вход через сессии
            session_start();
            $_SESSION['userId'] = $user['id'];
            header("Location: /catalog");
        } else {
            $errors['username'] = "Логин или пароль указаны неверно!";
        }
    }
}
require_once './login/login_form.php';