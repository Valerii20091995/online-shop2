<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['userId'])) {
    header("Location: ./profile");
}
function Validate(array $data): array
{
    $errors = [];
    if (isset($data['name'])) {
        $name = $data['name'];
        if (strlen($name) < 3) {
            $errors['name'] = "Имя не может содержать меньше 3 символов";
        }
    }
    if (isset($data['email'])) {
        $email = $data['email'];
        if (strlen($email) < 3) {
            $errors['email'] = "Email не может содержать меньше 3 символов";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Некорректный email";
        } else {
            $pdo =new DataBase("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            $userId = $_SESSION['userId'];
            if ($user && $user['email'] === $email && $user['id'] !== $userId) {
                $errors['email'] = "Этот Email уже зарегистрирован!";
            }
        }
    }
    return $errors;
}

$errors = Validate($_POST);

if (empty($errors)) {

    $userId = $_SESSION['userId'];
    $name = $_POST['name'];
    $email = $_POST['email'];


    $pdo = new DataBase("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
    $stmt = $pdo->query("SELECT * FROM users WHERE id = $userId");
    $user = $stmt->fetch();

    if ($user['name'] !== $name) {

        $stmt = $pdo->prepare("UPDATE users SET name = :name WHERE id = :userId");
        $stmt->execute([':name' => $name, ':userId' => $userId]);
    }

    if ($user['email'] !== $email) {
        $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :userId");
        $stmt->execute([':email' => $email, ':userId' => $userId]);
    }
    header("Location: ./profile");
    exit;
}
    require_once './editProfile/edit_profile_form.php';


