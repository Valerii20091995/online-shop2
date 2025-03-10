<?php

namespace Service;

use DTO\OrderCreateDTO;
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

    public function handleCheckOut(OrderCreateDTO $data)
    {
        $orderId = $this->orderModel->addOrder(
            $data->getName(),
            $data->getPhone(),
            $data->getComment(),
            $data->getAddress(),
            $data->getUser()->getId()
        );
        $userProducts = $this->userProductModel->getAllByUserId($data->getUser()->getId());

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $amount = $userProduct->getAmount();
            $this->orderProductModel->create($orderId, $productId, $amount);
        }
        $this->userProductModel->deleteOrder($data->getUser()->getId());
    }
}