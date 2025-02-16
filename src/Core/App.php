<?php
class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'registrate',
            ],
        ],
        '/login' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'Login',
            ],
        ],
        '/catalog' => [
            'GET' => [
                'class' => 'ProductController',
                'method' => 'Catalog',
            ],
            'POST' => [
                'class' => 'ProductController',
                'method' => 'getCatalog',
            ],
        ],
        '/profile' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'profile',
            ],
        ],
        '/profile-change' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getEditProfile',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'editProfile',
            ],
        ],
        '/add-product' => [
            'GET' => [
                'class' => 'ProductController',
                'method' => 'getAddProduct',
            ],
            'POST' => [
                'class' => 'ProductController',
                'method' => 'addProduct',
            ],
        ],
        '/cart' => [
            'GET' => [
                'class' => 'CartController',
                'method' => 'getCart',
            ],
        ],

    ];
    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if(isset($routes[$requestMethod])) {
            http_response_code(404);
            require_once './404.php';
            exit();
        }
        $routeMethods = $this->routes[$requestUri];
        if(!isset($routeMethods[$requestMethod])) {
            http_response_code(405);
            echo "HTTP метод $requestMethod не поддерживается";
        }
        $handler = $routeMethods[$requestMethod];
        $class = $handler['class'];
        $method = $handler['method'];
        $controller = new $class();
        $controller->$method();
    }
}

