<?php

namespace Service;

use DTO\AddProductDTO;
use DTO\DecreaseProductDTO;
use Model\Product;
use Model\UserProduct;
use Service\Auth\authInterface;
use Service\Auth\authSessionService;


class CartService
{
    private  UserProduct $userProductModel;
    private Product $productModel;
    private authInterface $authService;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->authService = new authSessionService();
    }
    public function addProduct(AddProductDTO $data)
    {
        $user = $this->authService->getCurrentUser();
        $userId = $user->getId();
        $amount = 1;
        $products = $this->userProductModel->getByUserProducts($userId, $data->getProductId());
        if ($products === null) {
            $this->userProductModel->addProductByUser($userId,$data->getProductId(), $amount);
        }else {
            $newAmount = 1 + $products->getAmount();
            $this->userProductModel->updateProductByUser($newAmount, $data->getProductId(), $userId);

        }
    }
    public function decreaseProduct(DecreaseProductDTO $data)
    {
        $user = $this->authService->getCurrentUser();
        $userId = $user->getId();
        $products = $this->userProductModel->getByUserProducts($userId, $data->getProductId());
        if ($products !== null) {
            $amount = $products->getAmount();
            if ($amount > 1) {
                $newAmount = $amount - 1;
                $this->userProductModel->updateProductByUser($newAmount, $data->getProductId(), $userId);
            } else {
                $this->userProductModel->removeProductInCart($data->getProductId(), $userId);
            }
        }
    }
    public function getUserProducts()
    {
        $user = $this->authService->getCurrentUser();

        if ($user == null) {
            return [];
        }
        $userProducts = $this->userProductModel->getAllByUserId($user->getId());
        foreach ($userProducts as $userProduct) {
            $product = $this->productModel->getOneById($userProduct->getProductId());
            $userProduct->setProduct($product);
            $totalSum = $userProduct->getAmount() * $product->getPrice();
            $userProduct->setTotalSum($totalSum);
        }
        return $userProducts;
    }
    public function getSum(array $userProducts):int
    {
        $total = 0;
        foreach ($this->getUserProducts() as $userProduct) {
            $total += $userProduct->getTotalSum();
        }
        return $total;
    }
}