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
            $amount = $this->cartService->decreaseProduct($dto);
            $result = [
                'amount' => $amount,
                'removed' => ($amount === 0)
            ];
            echo json_encode($result);
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
            $amount = $this->cartService->addProduct($dto);
            $result = [
                'amount' => $amount
            ];
            echo json_encode($result);
            exit();

        }
    }


}



