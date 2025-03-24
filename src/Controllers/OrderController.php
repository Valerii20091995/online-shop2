<?php
namespace Controllers;
use DTO\OrderCreateDTO;
use Request\OrderRequest;
use Service\CartService;
use Service\OrderService;
use Model\UserProduct;


class OrderController extends BaseController
{
    private OrderService $orderService;
    private CartService $cartService;

    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
    }
    public function getCheckOutForm()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $orderProducts = UserProduct::getAllByUserIdWithProducts($user->getId());
            if (empty($orderProducts)) {
                header('Location: /catalog');
                exit();
            }
            $userProducts = $this->cartService->getUserProducts();
            $total = $this->cartService->getSum($userProducts);
            require_once './../Views/order_form.php';
        } else {
            header('Location: /login');
            exit();
        }
    }
    public function handleCheckOut(OrderRequest $request)
    {
        if (!$user = $this->authService->getCurrentUser()) {
            header("Location: /login");
            exit();
        }
        $errors = $request->Validate();
        if (empty($errors)) {
            $dto = new OrderCreateDTO($request->getName(), $request->getPhone(),$request->getComment(),$request->getAddress());
            $this->orderService->handleCheckOut($dto);
            header('Location: /orders');
            exit();
        } else {
            $userProducts = $this->cartService->getUserProducts();
            $total = $this->cartService->getSum($userProducts);
            require_once '../Views/order_form.php';
        }
    }
    public function getAllOrders()
    {
        if (!$this->authService->check()) {
            header("Location: /login");
            exit();
        }
        $userOrders = $this->orderService->getAll();
        require_once '../Views/order_detail.php';
    }



}

