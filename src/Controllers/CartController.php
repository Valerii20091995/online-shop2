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
            $pdo = new PDO("pgsql:host=db; port=5432; dbname=mydb;", 'valera', 'qwerty');
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
