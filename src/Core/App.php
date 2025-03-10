<?php
namespace Core;
use Request\RegistrateRequest;
use Request\LoginRequest;

class App
{
    private array $routes = [];
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
                if ($method === 'registrate') {
                    $request = new RegistrateRequest($_POST);
                }elseif ($method === 'Login') {
                    $request = new LoginRequest($_POST);
                }
                $controller->$method($request);

            } else {
                echo "$requestMethod не поддерживается для $requestUri";
            }
        } else {
            http_response_code(404);
            require '../Views/404.php';

        }

    }
    public function get(string $route,string $className, string $method)
    {
        $this->routes[$route]['GET'] = [
            'class' =>  $className,
            'method' => $method,
        ];
    }
    public function post(string $route,string $className, string $method)
    {
        $this->routes[$route]['POST']= [
            'class' =>  $className,
            'method' => $method,
        ];
    }
}

