<?php
namespace Controllers;
use DTO\AddProductDTO;
use DTO\DecreaseProductDTO;
use Model\Product;
use Model\UserProduct;
use Service\CartService;

class CartController extends BaseController
{
    private UserProduct  $userProductModel;
    private Product $productModel;
    private CartService $cartService;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = New UserProduct();
        $this->productModel = new Product();
        $this->cartService = new CartService();
    }
    public function getCart():void
    {
        $products = [];
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $userProducts = $this->userProductModel->getAllByUserId($user->getId());

            foreach ($userProducts as $userProduct) {
                $productId = $userProduct->getProductId();
                $product = $this->productModel->getOneById($productId);
                $userProduct->setProduct($product);
                $products[] = $userProduct;
            }
        }
         require_once '../Views/cart_form.php';
    }
    public function decreaseProduct(array $data)
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }
        $errors = $this->ValidateAddProduct($_POST);
        if (empty($errors)) {
            $user = $this->authService->getCurrentUser();
            $dto = new DecreaseProductDTO($user, $data['product_id']);
            $this->cartService->decreaseProduct($dto);
            header('Location: /catalog');
            exit();
        }
    }
    public function addProduct(array $data)
    {

        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $errors = $this->ValidateAddProduct($_POST);
        if (empty($errors)) {
            $user = $this->authService->getCurrentUser();
            $dto = new AddProductDTO($user, $data['product_id']);
            $this->cartService->addProduct($dto);
            header('Location: /catalog');
            exit();
        }
    }
    private function ValidateAddProduct(array $data): array
    {
        $errors = [];
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



