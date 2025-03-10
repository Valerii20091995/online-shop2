<?php

namespace Service;

use DTO\AddProductDTO;
use DTO\DecreaseProductDTO;
use Model\UserProduct;


class CartService
{
    private $userProductModel;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
    }
    public function addProduct(AddProductDTO $data)
    {
        $amount = 1;
        $products = $this->userProductModel->getByUserProducts($data->getUser()->getId(), $data->getProductId());
        if ($products === null) {
            $this->userProductModel->addProductByUser($data->getUser()->getId(),$data->getProductId(), $amount);
        }else {
            $newAmount = 1 + $products->getAmount();
            $this->userProductModel->updateProductByUser($newAmount, $data->getProductId(), $data->getUser()->getId());

        }
    }
    public function decreaseProduct(DecreaseProductDTO $data)
    {
        $products = $this->userProductModel->getByUserProducts($data->getUser()->getId(), $data->getProductId());
        if ($products !== null) {
            $amount = $products->getAmount();
            if ($amount > 1) {
                $newAmount = $amount - 1;
                $this->userProductModel->updateProductByUser($newAmount, $data->getProductId(), $data->getUser()->getId());
            } else {
                $this->userProductModel->removeProductInCart($data->getProductId(), $data->getUser()->getId());
            }
        }
    }
}