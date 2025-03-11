<?php

use Controllers\CartController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;
use Controllers\ReviewController;
use Core\Autoloader;

require_once "./../Core/Autoloader.php";
//print_r(__DIR__);die;
$path =dirname(__DIR__);
\Core\Autoloader::register($path);
$app = new \Core\App();
$app->get('/registration',UserController::class, 'getRegistrate');
$app->post('/registration', UserController::class, 'registrate', \Request\RegistrateRequest::class);
$app->get('/login', UserController::class, 'getLogin');
$app->post('/login', UserController::class, 'Login', \Request\LoginRequest::class);
$app->get('/catalog', ProductController::class, 'Catalog');
$app->post('/catalog', ProductController::class, 'getCatalog');
$app->get('/profile', UserController::class, 'profile');
$app->get('/profile-change',UserController::class, 'getEditProfile');
$app->post('/profile-change', UserController::class, 'editProfile', Request\EditProfileRequest::class);
$app->post('/add-product', CartController::class, 'addProduct', \Request\AddProductRequest::class);
$app->get('/cart', CartController::class, 'getCart');
$app->get('/logout', UserController::class, 'logout');
$app->get('/order', OrderController::class, 'getCheckOutForm');
$app->post('/order', OrderController::class, 'handleCheckOut', Request\OrderRequest::class);
$app->get('/orders', OrderController::class, 'getAllOrders');
$app->post('/decrease-product', CartController::class, 'decreaseProduct',\Request\AddProductRequest::class);
$app->post('/product-review', ReviewController::class, 'getProduct');
$app->post('/reviews', ReviewController::class, 'addReview');



$app->run();
