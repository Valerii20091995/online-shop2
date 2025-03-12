<?php

namespace Request;

class AddReviewRequest extends Request
{
    public function getProductId(): int
    {
        return $this->data['product_id'];
    }
    public function getRating(): int
    {
        return $this->data['rating'];
    }
    public function getAuthor(): string
    {
        return $this->data['author'];
    }
    public function getProduct_review()
    {
        return $this->data['product_review'];
    }


}