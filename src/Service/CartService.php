<?php

namespace Service;

use DTO\AddProductDTO;
use DTO\DecreaseProductDTO;
use Model\Product;
use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\authSessionService;


class CartService
{
    private AuthInterface $authService;
    public function __construct()
    {
        $this->authService = new authSessionService();
    }
    public function addProduct(AddProductDTO $data):int
    {
        $user = $this->authService->getCurrentUser();
        $userId = $user->getId();
        $amount = 1;
        $products = UserProduct::getByUserProducts($userId, $data->getProductId());
        if ($products === null) {
            UserProduct::addProductByUser($userId,$data->getProductId(), $amount);
            return 1;
        }else {
            $newAmount = 1 + $products->getAmount();
            UserProduct::updateProductByUser($newAmount, $data->getProductId(), $userId);
            return $newAmount;

        }
    }
    public function decreaseProduct(DecreaseProductDTO $data):int
    {
        $user = $this->authService->getCurrentUser();
        $userId = $user->getId();
        $products = UserProduct::getByUserProducts($userId, $data->getProductId());
        if ($products === null) {
            return 0;
        }
        $amount = $products->getAmount();
        if ($amount > 1) {
            $newAmount = $amount - 1;
            UserProduct::updateProductByUser($newAmount, $data->getProductId(), $userId);
            return $newAmount;
        } else {
                UserProduct::removeProductInCart($data->getProductId(), $userId);
                return 0;
        }

    }
    public function getUserProducts()
    {
        $user = $this->authService->getCurrentUser();

        if ($user == null) {
            return [];
        }
        $userProducts = UserProduct::getAllByUserIdWithProducts($user->getId());
        foreach ($userProducts as $userProduct) {
            $totalSum = $userProduct->getAmount() * $userProduct->getProduct()->getPrice();
            $userProduct->setTotalSum($totalSum);
        }
        return $userProducts;
    }
    public function getSum():int
    {
        $total = 0;
        foreach ($this->getUserProducts() as $userProduct) {
            $total += $userProduct->getTotalSum();
        }
        return $total;
    }
}