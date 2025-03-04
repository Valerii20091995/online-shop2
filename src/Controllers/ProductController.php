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
            $userProducts = $this->userProductModel->getAllByUserId($user->getId());
            $products = $this->productModel->getByCatalog($user->getId());
            $newProducts = [];

            foreach ($products as $product) {
//                смотрим есть ли товар в корзине? используя false and true
                $issetProduct = false;
                foreach ($userProducts as $userProduct) {
                    if ($userProduct->getProductId() === $product->getId()) {
                        $userProduct->setProduct($product);
                        $newProducts[] = $userProduct;
                        $issetProduct = true;
                        break;
                    }
                }
                if (!$issetProduct) {
                    $userProduct = new UserProduct();
                    $userProduct->setProduct($product);
                    $userProduct->setAmount(0);
                    $newProducts[] = $userProduct;
                }
            }
            require_once '../Views/catalog_form.php';
        } else {
            header("Location: ./login");
        }
    }
    public function addProduct()
    {

        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

//        $errors = $this->ValidateAddProduct($_POST);
//        if (empty($errors)) {
            $user = $this->authService->getCurrentUser();
            $productId = (int)$_POST['product_id'];
//            $amount = (int)$_POST['amount'];
//            добавляем 1 ровно 1 штуку товара
            $amount = 1;
//проверяем есть ли товар в корзине
            $products = $this->userProductModel->getByUserProducts($user->getId(), $productId);
//            если нету товара то +1 штука продукта
            if ($products === null) {
                $this->userProductModel->addProductByUser($user->getId(),$productId, $amount);
            }else {
//                есть есть продукт в корзине то +1 штука продукта
                $newAmount = 1 + $products->getAmount();
                $this->userProductModel->updateProductByUser($newAmount, $productId, $user->getId());

            }
            header('Location: /catalog');
            exit();

//        }
    }
    public function decreaseProduct()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }
        $user = $this->authService->getCurrentUser();
        $productId = (int)$_POST['product_id'];

        $products = $this->userProductModel->getByUserProducts($user->getId(), $productId);
        if ($products !== null) {
            $amount = $products->getAmount();
            if ($amount > 1) {
                $newAmount = $amount - 1;
                $this->userProductModel->updateProductByUser($newAmount, $productId, $user->getId());
            } else {
                $this->userProductModel->removeProductInCart($productId, $user->getId());
            }
            header('Location: /catalog');
            exit();

        }
    }
//    private function ValidateAddProduct(array $data): array
//    {
//        $errors = [];
//        if (isset($data['amount'])) {
//            if (!filter_var($data['amount'], FILTER_VALIDATE_INT)) {
//                $errors['amount'] = 'Это поле должно являться целым числом';
//            } else {
//                $amount = (int)$data['amount'];
//
//                if ($amount < 1 || $amount > 1000) {
//                    $errors['amount'] = 'Количество товара не может быть меньше 1 или больше 100';
//                }
//            }
//        }
//        if (isset($data['product_id'])) {
//            $productId = (int)$data['product_id'];
//            $data = $this->productModel->getOneById($productId);
//            if ($data === false) {
//                $errors['product_id'] = 'Product не найден';
//            }
//        } else {
//            $errors['product_id'] = 'id продукта должен обязательно указан';
//        }
//
//
//        return $errors;
//    }
}