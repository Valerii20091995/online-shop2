<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Оформление заказа</h1>
    <form action="/order" method="POST">
        <div class="form-group">
            <label for="name">Имя получателя</label>
            <?php if (isset($errors['name'])): ?>
                 <label style="..."><?php echo $errors['name'];?></label>
            <?php endif; ?>
            <input type="text" id="name" name="name" required placeholder="Введите ваше имя">
        </div>

        <div class="form-group">
            <label for="address">Адрес</label>
            <?php if (isset($errors['address'])): ?>
                <label style="..."><?php echo $errors['address'];?></label>
            <?php endif; ?>
            <input type="text" id="address" name="address" required placeholder="Введите ваш адрес">
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <?php if (isset($errors['phone'])): ?>
                <label style="..."><?php echo $errors['phone'];?></label>
            <?php endif; ?>
            <input type="tel" id="phone" name="phone" required placeholder="Введите ваш телефон">
        </div>

        <div class="form-group">
            <label for="comment">Комментарии</label>
            <?php if (isset($errors['comment'])): ?>
                <label style="..."><?php echo $errors['comment'];?></label>
            <?php endif; ?>
            <textarea id="comment" name="comment" rows="4" placeholder="Дополнительные комментарии"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" value="Оформить заказ">
        </div>
        <div class="container">
            <?php foreach ($userProducts as $userProduct): ?>
            <h2><?php echo $userProduct->getProduct()->getName()?></h2>
            <label for="amount">Количество:</label>
            <input type="number" id="amount" name="amount" min="1" value=<?php echo $userProduct->getAmount()?> required>
            <label for="amount">Стоимость за 1 шт:</label>
            <div class="price">₽ <?php echo $userProduct->getProduct()->getPrice()?></div>
            <label for="totalProduct">Итого:</label>
            <div class="price">₽ <?php echo $userProduct->getTotalSum();?></div>
            <?php endforeach; ?>
            <h2><label for="totalOrder">Заказ на сумму:</label></h2>
            <div class="price">₽ <?php echo $total;?></div>

        </div>
        <button type="submit">Оформить заказ</button>
    </form>
</div>

</body>
</html>
