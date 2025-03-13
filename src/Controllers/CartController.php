<?php
namespace Controllers;
use DTO\AddProductDTO;
use DTO\DecreaseProductDTO;
use Request\AddProductRequest;
use Request\DecreaseProductRequest;
use Service\CartService;

class CartController extends BaseController
{
    private CartService $cartService;

    public function __construct()
    {
        parent::__construct();
        $this->cartService = new CartService();
    }
    public function getCart():void
    {
        $products = [];
        if ($this->authService->check()) {
            $userProducts = $this->cartService->getUserProducts();

        }
         require_once '../Views/cart_form.php';
    }
    public function decreaseProduct(DecreaseProductRequest $request)
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }
        $errors = $request->Validate();
        if (empty($errors)) {
            $dto = new DecreaseProductDTO($request->getProductId());
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

        $errors = $request->Validate();
        if (empty($errors)) {
            $dto = new AddProductDTO($request->getProductId());
            $this->cartService->addProduct($dto);
            header('Location: /catalog');
            exit();
        }
    }


}



