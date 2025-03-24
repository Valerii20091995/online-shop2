<div class="container">
    <nav class="navbar">

        <a href="/profile" class="navbar-link">Мой профиль</a>
        <a href="/cart" class="navbar-link">Корзина</a>
        <a href="/orders" class="navbar-link">Мои заказы</a>
        <a href="/logout" class="navbar-link">выйти из профиля</a>

    </nav>
    <h3 class="catalog-title">Catalog</h3>
    <div class="card-deck">
        <?php foreach ($newProducts as $userProduct): ?>
            <div class="card text-center">
                <a href="#">
                    <div class="card-header">
                        <span class="badge badge-success">Hit!</span>
                    </div>
                    <img class="card-img-top" src="<?php echo $userProduct->getProduct()->getimageUrl(); ?>" alt="Card image">
                    <div class="card-body">
                        <p class="product-name"><?php echo $userProduct->getProduct()->getName(); ?></p>
                        <p class="product-description"><?php echo $userProduct->getProduct()->getDescription(); ?></p>
                    </div>
                    <div class="card-footer">
                        <p class="price"><?php echo $userProduct->getProduct()->getPrice() . "р"; ?></p>
                    </div>
                </a>
            </div>

            <form  class="product-form" onsubmit="return false" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $userProduct->getProduct()->getId(); ?>" id="product_id" required>
                <input type="hidden" name="amount" value="1">
                <button type="submit" class="add-product-btn">+</button>
                <div class="product-quantity">
                    <?php echo $userProduct->getAmount() . " шт";
                    ?>
                </div>
            </form>
        <?php if ($userProduct->getAmount() > 0): ?>
            <form action="/decrease-product" method="POST" class="product-form">
                <input type="hidden" name="product_id" value="<?php echo $userProduct->getProduct()->getId(); ?>" id="product_id" required>
                <button type="submit"  class="remove-product-btn">-</button>
            </form>
            <?php endif; ?>
            <form action="/product-review" method="POST" class="product-form">
                <input type="hidden" name="product_id" value="<?php echo $userProduct->getProduct()->getId(); ?>" id="product_id" required>
                <button type="submit"  class="remove-product-btn">отзывы о продукте</button>
            </form>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $("document").ready(function () {
        // var form = $('.product-form');
        $('.product-form').submit(function () {
            $.ajax({
                type: "POST",
                url: "/add-product",
                data: $(this).serialize(),
                dataType: 'json',
                success: function (responce) {
                    console.log('Продукт добавлен в корзину')
                },
                error: function(xhr, status, error) {
                    console.error('Ошибка при добавлении товара:', error);
                }
            });
        });
    });
</script>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(135deg, #f3a683, #f7b7a3);
        margin: 0;
        padding: 0;
        color: #333;
    }

    .navbar {
        display: flex;
        justify-content: center;
        margin: 20px 0;
        background-color: #fff;
        padding: 10px 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .navbar-link {
        text-decoration: none;
        color: #ff6347;
        margin: 0 15px;
        font-size: 1.2rem;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    .navbar-link:hover {
        color: #d25e48;
    }

    .catalog-title {
        text-align: center;
        font-size: 2.5rem;
        margin-top: 20px;
        color: #fff;
    }

    .card-deck {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin: 30px 0;
    }

    .card {
        width: 250px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        text-align: center;
        margin-bottom: 20px;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-header {
        background-color: #ff6347;
        padding: 10px;
        color: white;
        font-weight: bold;
    }

    .badge {
        background-color: #28a745;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9rem;
    }

    .card-body {
        padding: 15px;
    }

    .product-name {
        font-size: 1.2rem;
        font-weight: bold;
        margin: 10px 0;
    }

    .product-description {
        font-size: 1rem;
        color: #777;
        margin-bottom: 10px;
    }

    .card-footer {
        background-color: #f8f9fa;
        padding: 10px;
    }

    .price {
        font-size: 1.2rem;
        font-weight: bold;
        color: #ff6347;
    }

    .product-form {
        text-align: center;
        margin-top: 20px;
    }

    .product-quantity {
        font-size: 1rem;
        color: #333;
        margin: 10px 0;
    }

    .product-btn {
        padding: 10px 20px;
        background-color: #ff6347;
        color: white;
        border: none;
        font-size: 1.5rem;
        font-weight: bold;
        border-radius: 50%;
        cursor: pointer;
        width: 50px;
        height: 50px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .product-btn:hover {
        background-color: #d25e48;
        transform: scale(1.1);
    }

    .product-btn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .remove-product-btn {
        background-color: #ff6347;
        border-radius: 50%;
        font-size: 1.5rem;
    }

    .add-product-btn {
        background-color: #28a745;
        border-radius: 50%;
        font-size: 1.5rem;
    }

    @media (max-width: 768px) {
        .card-deck {
            flex-direction: column;
            align-items: center;
        }

        .card {
            width: 100%;
            max-width: 350px;
        }
    }

</style>
