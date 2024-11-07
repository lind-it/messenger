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
    <form id="form" class="form" action="/auth/login" method="POST">
        <label for="email">Введите почту</label>
        <input type="text" name="email" placeholder="Введите почту">

        <label for="password">Введите пароль</label>
        <input type="text" name="password" placeholder="Введите пароль">

        <input type="submit" value="Войти">

        <? if (isset($_SESSION['error'])): ?>
            <p id="error" class="error">
                <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </p>
        <? endif ?>

        <p>Нет аккаунта? - <a href="/auth/sign-up">Зарегистрируйтесь</a></p>

    </form>

    <script src="/static/js/auth/auth.js"></script>
</body>
</html>
