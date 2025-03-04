<?php
namespace Controllers;
use Model\Product;
use Model\UserProduct;
use Service\authService;

class ProductController extends BaseController
{
    private $productModel;
    private $userProductModel;
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->userProductModel = new UserProduct();
    }

    public function getCatalog()
    {
        require_once '../Views/catalog_form.php';
    }

    public function Catalog()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $products = $this->productModel->getByCatalog($user->getId());
            foreach ($products as $product) {
                $productId = $product->getId();
                $amountInCart = isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId] : 0;
                $product->setAmountInCart($amountInCart);
            }
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

        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $errors = $this->ValidateAddProduct($_POST);
        if (empty($errors)) {
            $userId = $_SESSION['userId'];
            $productId = (int)$_POST['product_id'];
            $amount = (int)$_POST['amount'];

            $products = $this->userProductModel->getByUserProducts($userId, $productId);
            if ($products === null) {
                $this->userProductModel->addProductByUser($userId,$productId, $amount);
//                header('Location: /catalog');
            }else {
                $newAmount = $amount + $products->getAmount();
                $this->userProductModel->updateProductByUser($newAmount, $productId, $userId);

            }
            header('Location: /catalog');
            exit();

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
            $data = $this->productModel->getOneById($productId);
            if ($data === false) {
                $errors['product_id'] = 'Product не найден';
            }
        } else {
            $errors['product_id'] = 'id продукта должен обязательно указан';
        }


        return $errors;
    }
}