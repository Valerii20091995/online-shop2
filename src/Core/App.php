<?php
namespace Core;
use Request\RegistrateRequest;
use Request\LoginRequest;
use Service\Logger\LoggerFileErrorService;
use Service\Logger\LoggerInterface;
use Service\Logger\LoggerDataBaseService;

class App
{
    private LoggerInterface $loggerService;

    public function __construct()
    {
        $this->loggerService = new LoggerDataBaseService();
    }
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
                $requestClass = $handler['request'];
                try {
                    if ($requestClass !== null) {
                        $request = new $requestClass($_POST);
                        $controller->$method($request);
                    } else {
                        $controller->$method();
                    }
                } catch (\Throwable $exception) {
                    $this->loggerService->Logs($exception);
                    http_response_code(500);
                    require '../Views/500.php';
                }
            } else {
                echo "$requestMethod не поддерживается для $requestUri";
            }
        } else {
            http_response_code(404);
            require '../Views/404.php';

        }

    }

    public function get(string $route,string $className, string $method, $requestClass = null)
    {
        $this->routes[$route]['GET'] = [
            'class' =>  $className,
            'method' => $method,
            'request' => $requestClass

        ];
    }
    public function post(string $route,string $className, string $method,string $requestClass = null)
    {
        $this->routes[$route]['POST']= [
            'class' =>  $className,
            'method' => $method,
            'request' => $requestClass,
        ];
    }
}

