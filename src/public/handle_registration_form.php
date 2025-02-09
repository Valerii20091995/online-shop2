<?php


function Validate(array $post): array
{
    $errors = [];

// объявление и валидация данных
    if (isset($post['name'])) {
        $name = $post['name'];
        if (strlen($name) < 3) {
            $errors['name'] = "Имя не может содержать меньше 3 символов";
        }
    } else {
        $errors['name'] = "Имя должно быть заполнено";
    }

    if (isset($post['mail'])) {
        $email = $post['mail'];
        if (strlen($email) < 3) {
            $errors['email'] = "Email не может содержать меньше 3 символов";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Некорректный email";
        } else {
            // соединение с БД
            $pdo =new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $errors['email'] = "Этот Email уже зарегестрирован!";
            }
        }
    } else {
        $errors['email'] = "Email должен быть заполнен";
    }
// проверка совпадения паролей
    if (isset($post['psw'])) {
        $password = $post['psw'];
        if (strlen($password) < 3) {
            $errors['psw'] = "Пароль не может содержать меньше 3 символов";
        }
        $passwordRepeat = $post["psw-repeat"];
        if ($password !== $passwordRepeat) {
            $errors['psw-repeat'] = "Пароли не совпадают!";
        }
    } else {
        $errors['psw'] = "Пароль должен быть заполнен!";
    }
    return $errors;
}

$errors = Validate($_POST);

// внесение в БД, если нет ошибок
if (empty($errors)) {
    $name = $_POST['name'];
    $email = $_POST['mail'];
    $password = $_POST['psw'];
    $passwordRepeat = $_POST['psw-repeat'];
    $password = password_hash($password, PASSWORD_DEFAULT);

    $pdo =new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');

//добавление пользователей
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);

    $result = $stmt->fetch();
    print_r ($result);
}
require_once './registration_form.php';