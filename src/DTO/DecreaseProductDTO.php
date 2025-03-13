<?php

namespace DTO;

use Model\User;

class DecreaseProductDTO
{
    public function __construct(private int $productId)
    {}
    public function getProductId(): int
    {
        return $this->productId;
    }

}