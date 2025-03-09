<?php

namespace Controllers;
use Model\Product;
use Model\Review;
class ReviewController extends BaseController
{
    private  $reviewModel;
    private  $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->reviewModel = new Review();
        $this->productModel = new Product();
    }

    public function getProduct()
    {
        $productId = $_POST['product_id'];
//        $product = и сюда надо метод который получает из базы данных все о продукте
        $product = $this->productModel->getOneById($productId);
//        $reviews = $this->reviewModel->сюда закидываем метод который вытаскивает из бд все отзывы о продукте
        $reviews = $this->reviewModel->getReviewsByProductId($productId);
        if (!$reviews) {
            $reviews =[];
        }
        $averageRating = $this->reviewModel->getAverageRating($productId);
        require_once '../Views/review_form.php';
    }
    public function product()
    {
//   сюда должно прилетать методом пост все столбы из таблицы отзывов
        $productId = $_POST['product_id'];
        $rating = $_POST['rating'];
        $author = $_POST['author'];
        $product_review = $_POST['product_review'];
        $this->reviewModel->addReview($productId, $rating, $author, $product_review);
        header('Location: /catalog');
        exit();
    }



}