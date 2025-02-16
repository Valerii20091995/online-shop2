<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['userId'])) {
    header('Location: /login');
    exit();
}
function Validate(array $data): array
{
    $errors = [];
    if (isset($data['amount'])) {
            if (!filter_var($data['amount'], FILTER_VALIDATE_INT)) {
                $errors['amount'] = 'Это поле должно являться целым числом';
            } else {
                $amount = (int)$data['amount'];

                if ($amount < 1 || $amount > 1000) {
                    $errors['amount'] = 'Количество товара не может быть меньше 1 или больше 100';
                }
            }
        }
    if (isset($data['product_id'])) {
        $productId = (int)$data['product_id'];

        $pdo = new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $data = $stmt->fetch();

        if ($data === false) {
            $errors['product_id'] = 'Product не найден';
        }
    } else{
        $errors['product_id'] = 'id продукта должен обязательно указан';
    }


   return $errors;
}
$errors = Validate($_POST);
if (empty($errors)) {
    $pdo = new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
    $userId = $_SESSION['userId'];
    $productId = (int)$_POST['product_id'];
    $amount = (int)$_POST['amount'];
    $stmt =$pdo->prepare("SELECT *FROM user_products WHERE product_id = :productId AND user_id = :userId");
    $stmt->execute(['productId' => $productId, 'userId' => $userId]);
    $data = $stmt->fetch();
    if ($data === false) {
        $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
        header('Location: /cartController');
    }else {
        $amount = $amount + $data['amount'];
        $stmt = $pdo->prepare("UPDATE user_products SET amount = :amount WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['amount' => $amount, 'productId' => $productId, 'userId' => $userId]);
        header('Location: /cartController');
    }

}
require_once './add-productController/add_product_form.php';


