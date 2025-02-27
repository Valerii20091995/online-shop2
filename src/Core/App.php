<?php
namespace Core;
use Controllers\CartController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;



class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'registrate',
            ],
        ],
        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'Login',
            ],
        ],
        '/catalog' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'Catalog',
            ],
            'POST' => [
                'class' => ProductController::class,
                'method' => 'getCatalog',
            ],
        ],
        '/profile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'profile',
            ],
        ],
        '/profile-change' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getEditProfile',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'editProfile',
            ],
        ],
        '/add-product' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getAddProduct',
            ],
            'POST' => [
                'class' => ProductController::class,
                'method' => 'addProduct',
            ],
        ],
        '/cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'getCart',
            ],
        ],
        '/logout' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'logout',
            ],
        ],
        '/order' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getCheckOutForm',
            ],
            'POST' => [
                'class' => OrderController::class,
                'method' => 'handleCheckOut',
            ],
        ],
        '/orders' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getAllOrders',
            ],
        ],

    ];
    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if(isset($this->routes[$requestUri])) {
            $routeMethods = $this->routes[$requestUri];
            if (isset($routeMethods[$requestMethod])) {
                $handler = $routeMethods[$requestMethod];
                $class = $handler['class'];
                $method = $handler['method'];
                $controller = new $class();
                $controller->$method();

            } else {
                echo "$requestMethod не поддерживается для $requestUri";
            }
        } else {
            http_response_code(404);
            require '../Views/404.php';

        }

    }
}

