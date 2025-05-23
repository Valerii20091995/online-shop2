<?php
namespace Controllers;
use Model\Product;
use Model\UserProduct;


class ProductController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCatalog()
    {
        require_once '../Views/catalog_form.php';
    }

    public function Catalog()
    {
        if ($this->authService->check()) {

            $user = $this->authService->getCurrentUser();
            $userProducts = UserProduct::getAllByUserIdWithProducts($user->getId());
            $products = Product::getByCatalog($user->getId());
            $newProducts = [];
            $totalCount = 0;

            foreach ($products as $product) {
//                смотрим есть ли товар в корзине? используя false and true
                $issetProduct = false;
                foreach ($userProducts as $userProduct) {
                    if ($userProduct->getProductId() === $product->getId()) {
                        $userProduct->setProduct($product);
                        $newProducts[] = $userProduct;
                        $issetProduct = true;
                        $totalCount += $userProduct->getAmount();
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