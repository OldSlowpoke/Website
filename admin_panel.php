<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['login'])) {
    header('Location: form_login.php');
    exit();
}

// Проверка роли пользователя
if ($_SESSION['role'] !== 'admin') {
    echo "Доступ запрещен";
    exit();
}

// Код для административной панели
echo "Добро пожаловать, администратор!";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Административная панель</title>
</head>
<body>
    <h1>Административная панель</h1>
    <p>Здесь вы можете управлять пользователями и настройками системы.</p>
    <!-- Пример функции, доступной только администраторам -->
    <a href="manage_users.php">Управление пользователями</a>
    <a href="system_settings.php">Настройки системы</a>
    <a href="logout.php">Выйти</a>
</body>
</html>
