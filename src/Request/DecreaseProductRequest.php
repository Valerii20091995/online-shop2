<?php

namespace Request;

use Model\Product;

class DecreaseProductRequest extends Request
{
    private Product $productModel;
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->productModel = new Product();
    }
    public function getProductId(): int
    {
        return $this->data['product_id'];
    }
    public function ValidateAddProduct(): array
    {
        $errors = [];
        if (isset($this->data['product_id'])) {
            $productId = (int)$this->data['product_id'];
            $data = $this->productModel->getOneById($productId);
            if ($data === false) {
                $errors['product_id'] = 'Product не найден';
            }
        } else {
            $errors['product_id'] = 'id продукта должен обязательно указан';
        }


        return $errors;
    }

}