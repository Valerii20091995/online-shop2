<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой Профиль</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #ff6347; /* Цвет заголовка */
        }
        .profile-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: auto;
            width: 300px;
        }
        .button {
            background-color: #4CAF50; /* Зеленая кнопка */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 10px auto; /* Добавлено пространство между кнопками */
        }
        .button:hover {
            background-color: #45a049; /* Темнее при наведении */
        }
    </style>
</head>
<body>
<form action="/profile-change" method="POST">
    <div class="profile-container">
        <h1>Мой Профиль</h1>
        <p>Имя:  <?php echo $user['name']; ?> </p>
        <p>Email: <?php echo $user['email']; ?> </p>
        <a href="/profile-change" class="button">Изменить данные профиля</a>

        <a href="/catalog" class="button">Вернуться в каталог</a>
    </div>
</form>
</body>
</html>
