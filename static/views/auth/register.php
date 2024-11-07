<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/static/css/auth/auth.css">
</head>
<body>
    <form id="form" class="form" action="/auth/register" method="POST">
        <label for="name">Введите имя</label>
        <input type="text" name="name" placeholder="Введите имя">

        <label for="email">Введите почту</label>
        <input type="text" name="email" placeholder="Введите почту">

        <label for="password">Введите пароль</label>
        <input type="text" name="password" placeholder="Введите пароль">

        <label for="conf_pass">Подтвердите пароль</label>
        <input type="text" name="conf_pass" placeholder="Подтвердите пароль">

        <input type="submit" value="Зарегистрироваться">

        <p>Уже есть аккаунт? - <a href="/auth/sign-in">Войти</a></p>
        <? if (isset($_SESSION['error'])): ?>
            <p id="error" class="error">
                <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </p>
        <? endif ?>
    </form>
    <script src="/static/js/auth/register.js"></script>
</body>
</html>