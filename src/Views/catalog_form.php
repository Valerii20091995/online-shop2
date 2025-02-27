<div class="container">
    <nav class="navbar">

        <a href="/profile" class="navbar-link">Мой профиль</a>
        <a href="/cart" class="navbar-link">Корзина</a>
        <a href="/orders" class="navbar-link">Мои заказы</a>
        <a href="/logout" class="navbar-link">выйти из профиля</a>

    </nav>
    <h3 class="catalog-title">Catalog</h3>
    <div class="card-deck">
        <?php foreach ($products as $product): ?>
            <div class="card text-center">
                <a href="#">
                    <div class="card-header">
                        <span class="badge badge-success">Hit!</span>
                    </div>
                    <img class="card-img-top" src="<?php echo $product['image_url']; ?>" alt="Card image">
                    <div class="card-body">
                        <p class="product-name"><?php echo $product['name']; ?></p>
                        <p class="product-description"><?php echo $product['description']; ?></p>
                    </div>
                    <div class="card-footer">
                        <p class="price"><?php echo $product['price'] . "р"; ?></p>
                    </div>
                </a>
            </div>

            <form action="/add-product" method="POST" class="product-form">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" id="product_id" required>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <?php if (isset($errors['amount'])): ?>
                        <p class="error-message"><?php echo $errors['amount']; ?></p>
                    <?php endif; ?>
                    <input type="number" name="amount" id="amount" placeholder="Enter amount" required>
                </div>
                <button type="submit" class="add-product-btn">Add product</button>
            </form>
        <?php endforeach; ?>
    </div>
</div>

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

    .form-group {
        margin-bottom: 15px;
    }

    .form-group input {
        padding: 8px;
        font-size: 1rem;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 100%;
        max-width: 200px;
    }

    .form-group input:focus {
        outline: none;
        border-color: #ff6347;
    }

    .error-message {
        color: red;
        font-size: 0.9rem;
    }

    .add-product-btn {
        padding: 10px 20px;
        background-color: #ff6347;
        color: white;
        border: none;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .add-product-btn:hover {
        background-color: #d25e48;
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
