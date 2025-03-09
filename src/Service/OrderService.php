<?php

namespace Service;

use Model\Order;
use Model\OrderProduct;
use Model\UserProduct;

class OrderService
{
    private $orderProductModel;
    private $userProductModel;
    private $orderModel;
    public function __construct()
    {
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProduct();
        $this->orderModel = new Order();
    }

    public function handleCheckOut($data, $userId)
    {
        $name = $data['name'];
        $phone = $data['phone'];
        $comment = $data['comment'];
        $address = $data['address'];
        $orderId = $this->orderModel->addOrder($name, $phone, $comment, $address, $userId);
        $userProducts = $this->userProductModel->getAllByUserId($userId);

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $amount = $userProduct->getAmount();
            $this->orderProductModel->create($orderId, $productId, $amount);
        }
        $this->userProductModel->deleteOrder($userId);
    }
}