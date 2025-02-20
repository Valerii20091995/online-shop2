<?php
class ProductController
{
    public function getCatalog()
    {
        require_once '../Views/catalog_form.php';
    }

    public function Catalog()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
//
        if (isset($_SESSION['userId'])) {
            $productModel = new Product();
            $products = $productModel->getByCatalog($_SESSION['userId']);
            require_once '../Views/catalog_form.php';
        } else {
            header("Location: ./login");
        }
    }

    // Добавление продукта
    public function getAddProduct()
    {
        require_once '../Views/add_product_form.php';
    }

    public function addProduct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $errors = $this->ValidateAddProduct($_POST);
        if (empty($errors)) {
            $userId = $_SESSION['userId'];
            $productId = (int)$_POST['product_id'];
            $amount = (int)$_POST['amount'];
            $productModel = new User_Product();
            $products = $productModel->getByUserProducts($userId, $productId);
            if ($products === false) {
                $productModel->addProductByUser($userId,$productId, $amount);
                header('Location: /cart');
            }else {
                $newAmount = $amount + $products['amount'];
                $upProduct = $productModel->updateProductByUser($newAmount, $productId, $userId);
                header('Location: /cart');
            }

        }
        require_once '../Views/add_product_form.php';


    }

    private function ValidateAddProduct(array $data): array
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
            $productModel = new Product();
            $data = $productModel->getByProduct($productId);
            if ($data === false) {
                $errors['product_id'] = 'Product не найден';
            }
        } else {
            $errors['product_id'] = 'id продукта должен обязательно указан';
        }


        return $errors;
    }
}