<?php

namespace Controllers;
use Model\Product;
use Model\Review;
use Request\AddReviewRequest;

class ReviewController extends BaseController
{
    private Review $reviewModel;
    private Product  $productModel;

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
    public function addReview(AddReviewRequest $request)
    {
        $errors = $request->Validate();
        if (empty($errors)) {
            $this->reviewModel->addReview(
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