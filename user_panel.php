<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['login'])) {
    header('Location: form_login.php');
    exit();
}

// Проверка роли пользователя
if ($_SESSION['role'] !== 'user') {
    echo "Доступ запрещен";
    exit();
}

// Код для пользовательской панели
echo "Добро пожаловать, пользователь!";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Пользовательская панель</title>
</head>
<body>
    <h1>Пользовательская панель</h1>
    <p>Здесь вы можете просматривать и редактировать свои данные.</p>
    <!-- Пример функции, доступной только пользователям -->
    <a href="view_profile.php">Просмотр профиля</a>
    <a href="edit_profile.php">Редактирование профиля</a>
    <a href="logout.php">Выйти</a>
</body>
</html>
