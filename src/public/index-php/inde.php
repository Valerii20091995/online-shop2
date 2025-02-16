
public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

// регистрация
        if ($requestUri === '/registration') {
            require_once '../Controllers/UserController.php';
            $user = new UserController();
            if ($requestMethod === 'GET') {
                $user->getRegistrate();
            } elseif ($requestMethod === 'POST') {
                $user->registrate();
            } else {
                echo "HTTP метод $requestMethod не поддерживается";
            }

            // логин
        } elseif ($requestUri === '/login') {
            require_once '../Controllers/UserController.php';
            $user = new UserController();
            if ($requestMethod === 'GET') {
                $user->getLogin();
            } elseif ($requestMethod === 'POST') {
                $user->login();
            } else {
                echo "HTTP метод $requestMethod не работает";
            }

// каталог
        } elseif ($requestUri === '/catalog') {
            require_once '../Controllers/ProductController.php';
            $product = new ProductController();
            if ($requestMethod === 'POST') {
                $product->getCatalog();
            } elseif ($requestMethod === 'GET') {
                $product->Catalog();
            } else {
                echo "HTTP метод $requestMethod не поддерживается";
            }

            // выдача профиля
        } elseif ($requestUri === '/profile') {
            require_once '../Controllers/UserController.php';
            $user = new UserController();
            if ($requestMethod === 'POST') {
                require_once '../Views/profile_form.php';
            } elseif ($requestMethod === 'GET') {
                $user->profile();
            } else {
                echo "HTTP метод $requestMethod не поддерживается";
            }


            // изменение профиля
        } elseif ($requestUri === '/profile-change') {
            require_once '../Controllers/UserController.php';
            $user = new UserController();
            if ($requestMethod === 'GET') {
                $user->getEditProfile();
            } elseif ($requestMethod === 'POST') {
                $user->editProfile();
            } else {
                echo "HTTP метод $requestMethod не поддерживается";
            }
            // добавление в корзину продуктов
        } elseif ($requestUri === '/add-product') {
            require_once '../Controllers/ProductController.php';
            $product = new ProductController();
            if ($requestMethod === 'GET') {
                $product->getAddProduct();
            } elseif ($requestMethod === 'POST') {
                $product->addProduct();
            } else {
                echo "HTTP метод $requestMethod не поддерживается";
            }
        } elseif ($requestUri === '/cart') {
            require_once '../Controllers/CartController.php';
            $cart = new CartController();
            if ($requestMethod === 'GET') {
                $cart->getCart();
            } elseif ($requestMethod === 'POST') {
                $cart->getCart();
            } else {
                echo "HTTP метод $requestMethod не поддерживается";
            }
        } else {
            http_response_code(404);
            require_once './404.php';
        }

    }

