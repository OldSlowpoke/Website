<!DOCTYPE html>
<html>
<head>
    <title>Модуль регистрации</title>
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
    <!-- Подключение библиотеки jQuery -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <!-- Подключение jQuery плагина Masked Input для маски ввода телефона -->
    <script src="jquery.maskedinput.min.js"></script>
    <?php
    session_start();
    ?>
    <!-- Вывод сообщения о том, что логин занят -->
    <p class="prompt"><?php echo ($_SESSION['message'] . '<br>'); ?></p>
    <script>
        function check() {
            // Функция сравнивает содержание введенных паролей
            let a = document.getElementById("password").value;
            let b = document.getElementById("password1").value;
            if (a != b) {
                $('#password1').focus();
                let string = "Пароли не совпадают";
                ddd.innerHTML = string;
            } else {
                let string = " ";
                ddd.innerHTML = string;
            }
        }

        function pass() {
            // Функция удаляет из первого поля с паролем служебные символы и устанавливает ограничение на минимальное количество символов (равное 7)
            let passw = document.getElementById("password").value;
            passw = passw.replace(/[<>/.//,/$]/g, '');
            document.getElementById("password").value = passw;
            let col = passw.length;
            if (col < 7) {
                document.getElementById("password").value = '';
                let string = "Количество символов в пароле должно быть больше 6";
                dd.innerHTML = string;
            } else {
                let string = " ";
                dd.innerHTML = string;
            }
        }

        function nam(a) {
            // Функция удаляет из полей Фамилия и Имя служебные символы и цифры и делает первую букву введенного слова прописной (большой)
            let na = document.getElementById(a).value;
            na = na.replace(/[<>\.\/,\$0-9\s]/g, '');
            na = na.toLowerCase();
            na = FirstLetter(na);
            document.getElementById(a).value = na;
        }

        function FirstLetter(str) {
            // Функция делает первую букву (в текстовой переменной str) прописной (большой)
            return str[0].toUpperCase() + str.substring(1);
        }

        $(function() {
            // Код jQuery, устанавливающий маску для ввода телефона элементу input
            $('#Telephone').mask('+7(999) 999-9999');
        });
    </script>
</head>
<body>
    <div>
        <!-- Форма регистрации -->
        <!-- Эта форма отправляет данные на сервер для обработки регистрации пользователя. -->
        <!-- Метод POST используется для отправки данных на сервер, что делает их менее видимыми в URL. -->
        <!-- Действие формы (action) указывает на файл registration.php, который обрабатывает данные формы. -->
        <!-- Поля формы включают логин, пароль, подтверждение пароля, фамилию, имя, дату рождения и телефон. -->
        <form action="registration.php" method="POST">
            <p>
                <label>Ваш логин (E-mail):<br></label>
                <input name="login" id="login" type="email" size="20" maxlength="15" required>
            </p>
            <p>
                <label>Ваш пароль:<br></label>
                <input name="password" id="password" type="password" size="20" maxlength="15" value="<?php echo $_SESSION['password']; ?>" required onchange="pass()">
                <span id="dd" class="prompt"></span>
            </p>
            <p>
                <label>Введите еще раз пароль:<br></label>
                <input name="password1" id="password1" type="password" size="20" maxlength="15" value="<?php echo $_SESSION['password']; ?>" required onchange="check()">
                <span id="ddd" class="prompt"></span>
            </p>
            <p>
                <label>Фамилия:<br></label>
                <input name="surname" id="surname" type="text" size="20" maxlength="15" value="<?php echo $_SESSION['surname']; ?>" required onchange="nam('surname')">
            </p>
            <p>
                <label>Имя:<br></label>
                <input name="name" id="name" type="text" size="20" maxlength="15" value="<?php echo $_SESSION['name']; ?>" required onchange="nam('name')">
            </p>
            <p>
                <label>Дата рождения:<br></label>
                <input name="birthday" type="date" size="20" value="<?php echo $_SESSION['birthday']; ?>" required>
            </p>
            <p>
                <label>Телефон:<br></label>
                <input name="Telephone" id="Telephone" type="text" size="20" maxlength="11" value="<?php echo $_SESSION['telephone']; ?>">
            </p>
            <button type="submit" name="submit">Зарегистрироваться</button>
        </form>
    </div>
</body>
</html>
