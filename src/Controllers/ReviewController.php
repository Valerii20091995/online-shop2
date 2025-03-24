<?php

namespace Controllers;
use Model\Product;
use Model\Review;
use Request\AddReviewRequest;

class ReviewController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getProduct()
    {
        $productId = $_POST['product_id'];
//        $product = и сюда надо метод который получает из базы данных все о продукте
        $product = Product::getOneById($productId);
//        $reviews = $this->reviewModel->сюда закидываем метод который вытаскивает из бд все отзывы о продукте
        $reviews = Review::getReviewsByProductId($productId);
        if (!$reviews) {
            $reviews =[];
        }
        $averageRating = Review::getAverageRating($productId);
        require_once '../Views/review_form.php';
    }
    public function addReview(AddReviewRequest $request)
    {
        $errors = $request->Validate();
        if (empty($errors)) {
            Review::addReview(
                $request->getProductId(),
                $request->getRating(),
                $request->getAuthor(),
                $request->getProduct_review()
            );
            header('Location: /catalog');
            exit();
        }
    }



}