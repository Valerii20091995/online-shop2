<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\authSessionService;

class OrderService
{
    private OrderProduct $orderProductModel;
    private UserProduct $userProductModel;
    private Order $orderModel;
    private AuthInterface $authService;
    private Product $productModel;
    private CartService $cartService;
    public function __construct()
    {
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProduct();
        $this->orderModel = new Order();
        $this->authService = new authSessionService();
        $this->productModel = new Product();
        $this->cartService = new CartService();
    }

    public function handleCheckOut(OrderCreateDTO $data)
    {
        $sum = $this->cartService->getSum();
        if ($sum < 500) {
            throw new \Exception('Для оформления заказа сумма корзины должна быть больше 500');
        }

        $user = $this->authService->getCurrentUser();

        $orderId = $this->orderModel->addOrder(
            $data->getName(),
            $data->getPhone(),
            $data->getComment(),
            $data->getAddress(),
            $user->getId()
        );
        $userProducts = $this->userProductModel->getAllByUserId($user->getId());

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $amount = $userProduct->getAmount();
            $this->orderProductModel->create($orderId, $productId, $amount);
        }
        $this->userProductModel->deleteOrder($user->getId());
    }
    public function getAll():array
    {
        $user = $this->authService->getCurrentUser();
        $orders = $this->orderModel->getAllByUserId($user->getId());

        foreach ($orders as $userOrder) {
            $orderProducts = $this->orderProductModel->getAllByOrderId($userOrder->getId());
            $totalSum = 0;
            foreach ($orderProducts as $orderProduct) {
                $product = $this->productModel->getOneById($orderProduct->getProductId());
                $orderProduct->setProduct($product);
                $itemSum = $orderProduct->getAmount() * $product->getPrice();
                $orderProduct->setSum($itemSum);
                $totalSum = $totalSum + $itemSum;
            }
            $userOrder->setOrderProducts($orderProducts);
            $userOrder->setSum($totalSum);

        }
        return $orders;
    }

}