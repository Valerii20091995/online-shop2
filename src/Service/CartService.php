<?php

namespace Service;

use Model\UserProduct;


class CartService
{
    private $userProductModel;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
    }
    public function addProduct(int $productId,int $userId,int $amount)
    {
        $amount = 1;
        $products = $this->userProductModel->getByUserProducts($userId, $productId);
        if ($products === null) {
            $this->userProductModel->addProductByUser($userId,$productId, $amount);
        }else {
            $newAmount = 1 + $products->getAmount();
            $this->userProductModel->updateProductByUser($newAmount, $productId, $userId);

        }
    }
    public function decreaseProduct(int $productId,int $userId)
    {
        $products = $this->userProductModel->getByUserProducts($userId, $productId);
        if ($products !== null) {
            $amount = $products->getAmount();
            if ($amount > 1) {
                $newAmount = $amount - 1;
                $this->userProductModel->updateProductByUser($newAmount, $productId, $userId);
            } else {
                $this->userProductModel->removeProductInCart($productId, $userId);
            }
        }
    }
}