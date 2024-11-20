<?php
session_start();
include 'connect.php';

// Получение данных из формы регистрации
$login = trim(strip_tags($_POST['login']));
$password = trim(strip_tags($_POST['password']));
$surname = trim(strip_tags($_POST['surname']));
$name = trim(strip_tags($_POST['name']));
$birthday = trim(strip_tags($_POST['birthday']));
$telephone = trim(strip_tags($_POST['telephone']));

// Хеширование пароля с использованием password_hash
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Проверка, существует ли уже такой логин
$query = "SELECT Login FROM `User` WHERE `Login` = '$login'";
$check_login = $conn->query($query);
$_SESSION['message'] = '';
if ($check_login->num_rows > 0) {
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
$query = "INSERT INTO User (Login, Password, Surname, Name, Birthday, Telephone) VALUES ('$login', '$hashed_password', '$surname', '$name', '$birthday', '$telephone')";
$res = $conn->query($query);
if (!$res) {
    echo "ОШИБКА в запросе к БД";
} else {
    echo "Запись в базу данных прошла успешно";
}

// Закрытие соединения
$conn->close();
?>
