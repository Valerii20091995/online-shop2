<?php

namespace Request;
use Model\Product;
class AddProductRequest extends Request
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
    public function getProductId(): int
    {
        return $this->data['product_id'];
    }
    public function Validate(): array
    {
        $errors = [];
        if (isset($this->data['product_id'])) {
            $productId = (int)$this->data['product_id'];
            $data = Product::getOneById($productId);
            if ($data === false) {
                $errors['product_id'] = 'Product не найден';
            }
        } else {
            $errors['product_id'] = 'id продукта должен обязательно указан';
        }


        return $errors;
    }

}