<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Общий стиль для страницы */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Стиль для контейнера с заказами */
        .order-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Стиль для каждого карточки заказа */
        .order-card {
            margin-bottom: 20px;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
        }

        .order-card h2 {
            font-size: 1.5em;
            color: #333;
        }

        .order-card p {
            font-size: 1.1em;
            color: #555;
            margin: 5px 0;
        }

        /* Стиль для таблицы */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
            color: #333;
        }

        /* Стиль для ссылки */
        a {
            font-size: 1.2em;
            color: #0066cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Стили для общего отображения суммы заказа */
        .order-card p:last-child {
            font-weight: bold;
            font-size: 1.2em;
            color: #2d2d2d;
        }

    </style>
</head>
<body>
<a href="/catalog">Каталог</a>
<h1>Мои Заказы</h1>
<div class="order-container">
    <?php foreach ($userOrders as $newUserOrder): ?>
        <div class="order-card">
            <h2>Заказ № <?php echo $newUserOrder->getId()?></h2>
            <p><?php echo $newUserOrder->getName()?></p>
            <p><?php echo $newUserOrder->getPhone()?></p>
            <p><?php echo $newUserOrder->getComment()?></p>
            <p><?php echo $newUserOrder->getAddress();?></p>
            <table>
                <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Количество</th>
                    <th>Стоимость</th>
                    <th>Сумма</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($newUserOrder->getProducts() as $userProduct): ?>
                    <tr>
                        <td><?php echo $userProduct->getProduct()->getName()?></td>
                        <td><?php echo $userProduct->getAmount()?></td>
                        <td><?php echo $userProduct->getProduct()->getPrice()?></td>
                        <td><?php echo $userProduct->getSum()?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p>Сумма заказа <?php echo $newUserOrder->getSum();?></p>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
