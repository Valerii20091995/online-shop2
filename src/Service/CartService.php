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
    public function addProduct(AddProductDTO $data)
    {
        $user = $this->authService->getCurrentUser();
        $userId = $user->getId();
        $amount = 1;
        $products = UserProduct::getByUserProducts($userId, $data->getProductId());
        if ($products === null) {
            UserProduct::addProductByUser($userId,$data->getProductId(), $amount);
        }else {
            $newAmount = 1 + $products->getAmount();
            UserProduct::updateProductByUser($newAmount, $data->getProductId(), $userId);

        }
    }
    public function decreaseProduct(DecreaseProductDTO $data)
    {
        $user = $this->authService->getCurrentUser();
        $userId = $user->getId();
        $products = UserProduct::getByUserProducts($userId, $data->getProductId());
        if ($products !== null) {
            $amount = $products->getAmount();
            if ($amount > 1) {
                $newAmount = $amount - 1;
                UserProduct::updateProductByUser($newAmount, $data->getProductId(), $userId);
            } else {
                UserProduct::removeProductInCart($data->getProductId(), $userId);
            }
        }
    }
    public function getUserProducts()
    {
        $user = $this->authService->getCurrentUser();

        if ($user == null) {
            return [];
        }
        return UserProduct::getAllByUserIdWithProducts($user->getId());
//        $userProducts = UserProduct::getAllByUserIdWithProducts($user->getId());
//        foreach ($userProducts as $userProduct) {
//            $product = Product::getOneById($userProduct->getProductId());
//            $userProduct->setProduct($product);
//            $totalSum = $userProduct->getAmount() * $product->getPrice();
//            $userProduct->setTotalSum($totalSum);
//        }
//        return $userProducts;
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