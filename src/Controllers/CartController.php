<?php
namespace Controllers;
use DTO\AddProductDTO;
use DTO\DecreaseProductDTO;
use Model\Product;
use Model\UserProduct;
use Request\AddProductRequest;
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
    public function decreaseProduct(AddProductRequest $request)
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }
        $errors = $request->ValidateAddProduct();
        if (empty($errors)) {
            $user = $this->authService->getCurrentUser();
            $dto = new DecreaseProductDTO($user, $request->getProductId());
            $this->cartService->decreaseProduct($dto);
            header('Location: /catalog');
            exit();
        }
    }
    public function addProduct(AddProductRequest $request)
    {

        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $errors = $request->validateAddProduct();
        if (empty($errors)) {
            $user = $this->authService->getCurrentUser();
            $dto = new AddProductDTO($user, $request->getProductId());
            $this->cartService->addProduct($dto);
            header('Location: /catalog');
            exit();
        }
    }


}



