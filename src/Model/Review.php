<?php

namespace Model;

class Review extends Model
{
    private $id;
    private $product_id;
    private $rating;
    private $author;
    private $product_review;
    private $date;
    protected function getTableName():string
    {
        return "reviews";
    }
    private function createObject(array $review):self|null
    {
        if(!$review) {
            return null;
        }
        $object = new self();
        $object->id = $review['id'];
        $object->product_id = $review['product_id'];
        $object->rating = $review['rating'];
        $object->author = $review['author'];
        $object->product_review = $review['product_review'];
        $object->date = $review['date'];
        return $object;
    }
    public function getReviewsByProductId($productId) {

        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE product_id = :productId");
        $stmt->execute(['productId' => $productId]);
        $data = $stmt->fetchAll();
        $reviews = [];
        foreach ($data as $review) {
            $reviews[] = $this->createObject($review);
        }
        return $reviews;
    }
    public function getAverageRating($productId)
    {
        $stmt = $this->pdo->prepare("SELECT rating FROM {$this->getTableName()} WHERE product_id = :productId");
        $stmt->execute(['productId' => $productId]);
        $data = $stmt->fetchAll();
        if (empty($data)) {
            return 0;
        }
        $ratings = array_column($data, 'rating');
        $sum =array_sum($ratings);
        $count = count($ratings);
        return round($sum/$count, 2);


    }
    public function addReview($productId, $rating, $author, $product_review):void
    {
        $stmt =$this->pdo->prepare("INSERT INTO {$this->getTableName()} (product_id, rating, author, product_review) VALUES (:productId, :rating, :author, :review)");
        $stmt->execute(['productId' => $productId, 'rating' => $rating, 'author' => $author, 'review' => $product_review]);
    }
    public function getId()
    {
        return $this->id;
    }
    public function getProductId()
    {
        return $this->product_id;
    }
    public function getRating()
    {
        return $this->rating;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    public function getProductReview()
    {
        return $this->product_review;
    }
    public function getDate()
    {
        return $this->date;
    }


}
