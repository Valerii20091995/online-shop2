
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

    CART:
<?php

class CartController
{
    public function getCart()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $products = [];
        if (isset($_SESSION['userId'])) {
            $pdo = new DataBase("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
            $stmt = $pdo->query('SELECT * FROM user_products WHERE user_id = ' . $_SESSION['userId']);
            $userProducts = $stmt->fetchAll();

            foreach ($userProducts as $userProduct) {
                $productId = $userProduct['product_id'];
                $productStmt = $pdo->query("SELECT * FROM products WHERE id = $productId");
                $product = $productStmt->fetch();
                $product['amount'] = $userProduct['amount'];
                $products[] = $product;
            }
        }
        return $products;
    }

}

$cart = new cartController();
$products = $cart->getCart();
?>
<div class="container">
    <h3 class="page-title">Корзина с товаром</h3>
    <a href="/catalog" class="back-to-catalog">Вернуться в каталог</a>
    <div class="card-deck">
        <?php foreach ($products as $product): ?>
            <div class="card text-center">
                <a href="#">
                    <div class="card-header">
                        <span class="badge badge-success">Hit!</span>
                    </div>
                    <div class="card-body">
                        <img class="card-img-top" src="<?php echo $product['image_url']; ?>" alt="Card image">
                        <p class="card-text product-name"><?php echo $product['name']; ?></p>
                        <p class="card-text description"><?php echo $product['description']; ?></p>
                        <div class="card-footer">
                            <p class="price"><?php echo "Цена: " . $product['price'] . "р"; ?></p>
                            <p class="amount"><?php echo "Количество: " . $product['amount'] . "шт"; ?></p>
                            <p class="total"><?php echo "Итого: " . $product['amount'] * $product['price'] . "р"; ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>


