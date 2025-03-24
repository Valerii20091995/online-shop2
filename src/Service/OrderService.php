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
    private AuthInterface $authService;
    private CartService $cartService;
    public function __construct()
    {
        $this->authService = new authSessionService();
        $this->cartService = new CartService();
    }

    public function handleCheckOut(OrderCreateDTO $data)
    {
        $sum = $this->cartService->getSum();
        if ($sum < 500) {
            throw new \Exception('Для оформления заказа сумма корзины должна быть больше 500');
        }

        $user = $this->authService->getCurrentUser();

        $orderId = Order::addOrder(
            $data->getName(),
            $data->getPhone(),
            $data->getComment(),
            $data->getAddress(),
            $user->getId()
        );
        $userProducts = UserProduct::getAllByUserIdWithProducts($user->getId());

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $amount = $userProduct->getAmount();
            OrderProduct::create($orderId, $productId, $amount);
        }
        UserProduct::deleteOrder($user->getId());
    }
    public function getAll():array
    {
        $user = $this->authService->getCurrentUser();
        $orders = Order::getAllByUserId($user->getId());

//        foreach ($orders as $order) {
//            $totalSum = 0;
//            foreach ($order->getOrderProducts() as $orderProduct) {
//                $totalSum += $orderProduct->getAmount() * $orderProduct->getPrice();
//            }
//            $order->setSum($totalSum);
//        }
//        print_r($orders);
//        die;
//        return $orders;
//
//    }

        foreach ($orders as $userOrder) {
            $orderProducts = OrderProduct::getAllByOrderId($userOrder->getId());
            $totalSum = 0;
            $newUserOrders = [];
            foreach ($orderProducts as $orderProduct) {
                $product = Product::getOneById($orderProduct->getProductId());
                $orderProduct->setProduct($product);
                $itemSum = $orderProduct->getAmount() * $product->getPrice();
                $orderProduct->setSum($itemSum);
                $totalSum = $totalSum + $itemSum;
                $newUserOrders[] = $orderProduct;
            }
            $userOrder->setOrderProducts($newUserOrders);
            $userOrder->setSum($totalSum);

        }
        return $orders;
    }


}