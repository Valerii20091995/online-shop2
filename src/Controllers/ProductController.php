<?php
namespace Controllers;
use Model\Product;
use Model\UserProduct;
use Service\CartService;


class ProductController extends BaseController
{
    private Product $productModel;
    private UserProduct $userProductModel;
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->userProductModel = new UserProduct();
    }

    public function getCatalog()
    {
        require_once '../Views/catalog_form.php';
    }

    public function Catalog()
    {
        if ($this->authService->check()) {

            $user = $this->authService->getCurrentUser();
            $userProducts = $this->userProductModel->getAllByUserId($user->getId());
            $products = $this->productModel->getByCatalog($user->getId());
            $newProducts = [];

            foreach ($products as $product) {
//                смотрим есть ли товар в корзине? используя false and true
                $issetProduct = false;
                foreach ($userProducts as $userProduct) {
                    if ($userProduct->getProductId() === $product->getId()) {
                        $userProduct->setProduct($product);
                        $newProducts[] = $userProduct;
                        $issetProduct = true;
                        break;
                    }
                }
                if (!$issetProduct) {
                    $userProduct = new UserProduct();
                    $userProduct->setProduct($product);
                    $userProduct->setAmount(0);
                    $newProducts[] = $userProduct;
                }
            }
            require_once '../Views/catalog_form.php';
        } else {
            header("Location: ./login");
        }
    }
}