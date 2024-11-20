<?php
session_start();

// Проверка, вошел ли пользователь в систему и является ли он администратором
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

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
