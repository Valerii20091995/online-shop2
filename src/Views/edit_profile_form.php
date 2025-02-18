
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование профиля</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Редактирование профиля</h2>
    <form action="profile-change" method="POST">
        <div class="form-group">
            <label for="name">Имя:</label>
            <?php if (isset($errors['name'])):  ?>
                <label style="color: red"><?php echo $errors['name'];?></label>
            <?php endif; ?>
            <input type="text" id="name" name="name" value="<?php echo $user['name'];?>" >
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <?php if (isset($errors['email'])):  ?>
                <label style="color: red"><?php echo $errors['email'];?></label>
            <?php endif; ?>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" >
        </div>
        <button type="submit">Сохранить изменения</button>
    </form>
</div>

</body>
</html>
