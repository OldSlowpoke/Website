<?php
session_start();
// Получение данных из формы регистрации
$login = trim(strip_tags($_POST['login']));
$password = trim(strip_tags($_POST['password']));
$surname = trim(strip_tags($_POST['surname']));
$name = trim(strip_tags($_POST['name']));
$birthday = trim(strip_tags($_POST['birthday']));
$telephone = trim(strip_tags($_POST['telephone']));

// Подключение к базе данных
$link = mysqli_connect('localhost', 'regular_user', 'strong_password', 'DB');
if (!$link) die('Ошибка соединения с БД');

// Проверка, существует ли уже такой логин
$query = "SELECT Login FROM `User` WHERE `Login` = '$login'";
$check_login = mysqli_query($link, $query);
$_SESSION['message'] = '';
if (mysqli_num_rows($check_login) > 0) {
    // Если логин занят, сохраняем данные в сессии и перенаправляем на форму регистрации
    $_SESSION['message'] = 'Введите другой логин. Введенный логин уже занят.';
    $_SESSION['login'] = $login;
    $_SESSION['password'] = $password;
    $_SESSION['surname'] = $surname;
    $_SESSION['name'] = $name;
    $_SESSION['birthday'] = $birthday;
    $_SESSION['telephone'] = $telephone;
    header('Location: form_registration.php');
    exit();
}

// Вставка нового пользователя в базу данных
$query = "INSERT INTO User (Login, Password, Surname, Name, Birthday, Telephone) VALUES ('$login', '$password', '$surname', '$name', '$birthday', '$telephone')";
$res = mysqli_query($link, $query);
if (!$res) {
    echo "ОШИБКА в запросе к БД";
} else {
    echo "Запись в базу данных прошла успешно";
}
?>
