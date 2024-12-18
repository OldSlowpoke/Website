<!DOCTYPE html>
<html>
<head>
    <title>Вход в систему</title>
    <style>
        body {
            background: #dff;
            font-family: Verdana;
            font-size: 18px;
        }
        div {
            margin-left: 200px;
            margin-top: 50px;
        }
        .prompt {
            font-family: Verdana;
            font-size: 12px;
            font-style: italic;
            color: #f00;
        }
    </style>
</head>
<body>
    <div>
        <!-- Форма входа -->
        <!-- Эта форма отправляет данные на сервер для обработки входа пользователя. -->
        <!-- Метод POST используется для отправки данных на сервер, что делает их менее видимыми в URL. -->
        <!-- Действие формы (action) указывает на файл login.php, который обрабатывает данные формы. -->
        <!-- Поля формы включают логин и пароль. -->
        <form action="login.php" method="POST">
            <p>
                <label>Ваш логин (E-mail):<br></label>
                <input name="login" id="login" type="email" size="20" maxlength="30" required>
            </p>
            <p>
                <label>Ваш пароль:<br></label>
                <input name="password" id="password" type="password" size="20" maxlength="15" required>
            </p>
            <button type="submit" name="submit">Войти</button>
        </form>
    </div>
</body>
</html>
