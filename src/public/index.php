<?php

use Controllers\CartController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;
use Core\Autoloader;

require_once "./../Core/Autoloader.php";
//print_r(__DIR__);die;
$path =dirname(__DIR__);
\Core\Autoloader::register($path);
$app = new \Core\App();
$app->addRoute('/registration', 'GET', UserController::class, 'getRegistrate');
$app->addRoute('/registration', 'POST', UserController::class, 'registrate');
$app->addRoute('/login', 'GET', UserController::class, 'getLogin');
$app->addRoute('/login', 'POST', UserController::class, 'Login');
$app->addRoute('/catalog', 'GET', ProductController::class, 'Catalog');
$app->addRoute('/catalog', 'POST', ProductController::class, 'getCatalog');
$app->addRoute('/profile', 'GET', UserController::class, 'profile');
$app->addRoute('/profile-change', 'GET', UserController::class, 'getEditProfile');
$app->addRoute('/profile-change', 'POST', UserController::class, 'editProfile');
$app->addRoute('/add-product', 'GET', ProductController::class, 'getAddProduct');
$app->addRoute('/add-product', 'POST', ProductController::class, 'addProduct');
$app->addRoute('/cart', 'GET', CartController::class, 'getCart');
$app->addRoute('/logout', 'GET', UserController::class, 'logout');
$app->addRoute('/order', 'GET', OrderController::class, 'getCheckOutForm');
$app->addRoute('/order', 'POST', OrderController::class, 'handleCheckOut');
$app->addRoute('/orders', 'GET', OrderController::class, 'getAllOrders');


$app->run();
