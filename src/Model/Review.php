<?php

namespace Model;

class Review extends Model
{
    private int $id;
    private int $product_id;
    private int $rating;
    private  string $author;
    private string $product_review;
    private $date;
    protected static function getTableName():string
    {
        return "reviews";
    }
    public static function createObject(array $review):self|null
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
    public static function getReviewsByProductId(int $productId):array
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("SELECT * FROM $tableName WHERE product_id = :productId");
        $stmt->execute(['productId' => $productId]);
        $data = $stmt->fetchAll();
        $reviews = [];
        foreach ($data as $review) {
            $reviews[] = self::createObject($review);
        }
        return $reviews;
    }
    public static function getAverageRating(int $productId):int|float
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("SELECT rating FROM $tableName WHERE product_id = :productId");
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
    public static function addReview(int $productId,int $rating,string $author,string $product_review)
    {
        $tableName = static::getTableName();
        $stmt =self::getPDO()->prepare(
            "INSERT INTO $tableName (product_id, rating, author, product_review)
            VALUES (:productId, :rating, :author, :review)");
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
