<?php
namespace Controllers;
use Model\Product;
use Model\UserProduct;
use Service\authService;

class CartController extends BaseController
{
    private UserProduct  $userProductModel;
    private Product $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = New UserProduct();
        $this->productModel = new Product();
    }
    public function getCart():array|false
    {
        $products = [];
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $userProducts = $this->userProductModel->getAllByUserId($user->getId());

            foreach ($userProducts as $userProduct) {
                $productId = $userProduct->getProductId();
                $product = $this->productModel->getOneById($productId);
                $userProduct->setProduct($product);
                $products[] = $userProduct;
            }
        }
         return $products;
    }
//    функция которая увеличивает количество товара на 1 единицу
    public function addInCart($productId)
    {
        $this->authService->startSession();
//        если товар уже есть каком то количество в заказе то увеличиваем инкрементом на 1 единицу
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] ++;
        } else {
//            если товара изначально не было в заказе то добавляем 1 единицу
            $_SESSION['cart'][$productId] = 1;
        }
        header('Location: /catalog');
        exit;
    }
//    функция для уменьшения либо же удаления товара из корзины
    public  function decreaseInCart($productId)
    {
        $this->authService->startSession();
//        если товар вообще уже ест ьв заказе?
        if (isset($_SESSION['cart'][$productId])) {
//            если товара больше 1 единицы то уменьшаем на 1
            if ($_SESSION['cart'][$productId] > 1) {
                $_SESSION['cart'][$productId] --;
            } else {
                unset($_SESSION['cart'][$productId]);
            }
        }
        header('Location: /catalog');
        exit;
    }
    public function getAmountInCart($productId)
    {
        $this->authService->startSession();

        // Если товар в корзине
        if (isset($_SESSION['cart'][$productId])) {
            return $_SESSION['cart'][$productId];
        }

        return 0; // Если товара нет в корзине
    }
    public function decreaseProduct()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }
        if (empty($errors)) {
            $user = $this->authService->getCurrentUser();
            $productId = (int)$_POST['product_id'];

            $products = $this->userProductModel->getByUserProducts($user->getId(), $productId);
            if ($products === null) {
                header('Location: /catalog');
                exit();
            }else {
                $amountInCart = $products->getAmount();
                if ($amountInCart - 1) {
                    $this->userProductModel->updateProductByUser($amountInCart, $productId, $user->getId());
                } else {
                    $this->userProductModel->removeProductByUser($user->getId(), $productId, $amountInCart);
                }

            }
            header('Location: /catalog');
            exit();

        }
        require_once '../Views/add_product_form.php';


    }
    public function removeProductByUser($productId)
    {
        $this->authService->startSession();

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
                        <img class="card-img-top" src="<?php echo $product->getProduct()->getImageUrl(); ?>" alt="Card image">
                        <p class="card-text product-name"><?php echo $product->getProduct()->getName(); ?></p>
                        <p class="card-text description"><?php echo $product->getProduct()->getDescription(); ?></p>
                        <div class="card-footer">
                            <p class="price"><?php echo "Цена: " . $product->getProduct()->getPrice() . "р"; ?></p>
                            <p class="amount"><?php echo "Количество: " . $product->getAmount() . "шт"; ?></p>
                            <p class="total"><?php echo "Итого: " . $product->getAmount() * $product->getProduct()->getPrice() . "р"; ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="/order" class="page-title">Оформить заказ</a>
</div>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f9f9f9;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        padding: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 30px;
        text-align: center;
    }

    .back-to-catalog {
        display: block;
        text-align: center;
        font-size: 1.2rem;
        color: #007bff;
        text-decoration: none;
        margin-bottom: 30px;
    }

    .back-to-catalog:hover {
        text-decoration: underline;
    }

    .card-deck {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 18rem;
        flex: 0 1 calc(33.333% - 20px);
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background-color: #f7f7f7;
        padding: 10px;
        text-align: center;
    }

    .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
        background-color: #28a745;
        color: #fff;
        border-radius: 20px;
    }

    .card-body {
        padding: 20px;
        text-align: center;
    }

    .card-img-top {
        max-width: 100%;
        height: auto;
        border-bottom: 1px solid #eaeaea;
    }

    .product-name {
        font-size: 1.3rem;
        font-weight: 600;
        margin-top: 15px;
    }

    .description {
        font-size: 1rem;
        color: #555;
        margin: 10px 0;
    }

    .card-footer {
        background-color: #f7f7f7;
        padding: 15px;
        text-align: center;
    }

    .price, .amount, .total {
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
    }

    .price {
        color: #007bff;
    }

    .total {
        font-size: 1.2rem;
        font-weight: 600;
        color: #28a745;
    }

    /* Мобильная адаптивность */
    @media (max-width: 768px) {
        .card {
            flex: 0 1 calc(50% - 20px);
        }
    }

    @media (max-width: 480px) {
        .card {
            flex: 0 1 100%;
        }

        .page-title {
            font-size: 1.8rem;
        }
    }
</style>

