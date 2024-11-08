<?php
session_start();
// Получение данных из формы входа
$login = trim(strip_tags($_POST['login']));
$password = trim(strip_tags($_POST['password']));

// Подключение к базе данных
$link = mysqli_connect('localhost', 'regular_user', 'strong_password', 'DB');
if (!$link) die('Ошибка соединения с БД');

// Проверка логина и пароля
$query = "SELECT * FROM `User` WHERE `Login` = '$login' AND `Password` = '$password'";
$result = mysqli_query($link, $query);

if (mysqli_num_rows($result) > 0) {
    // Если логин и пароль верны, сохраняем логин и роль в сессии
    $user = mysqli_fetch_assoc($result);
    $_SESSION['login'] = $login;
    $_SESSION['role'] = $user['Role'];

    // Перенаправление на соответствующую панель в зависимости от роли
    switch ($user['Role']) {
        case 'admin':
            header('Location: admin_panel.php');
            break;
        case 'user':
            header('Location: user_panel.php');
            break;
        case 'seller':
            header('Location: seller_panel.php');
            break;
        case 'owner':
            header('Location: owner_panel.php');
            break;
        default:
            echo "Неизвестная роль";
            exit();
    }
    exit();
} else {
    // Если логин или пароль неверны, выводим сообщение об ошибке
    echo "Неверный логин или пароль";
}
?>
