<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['login'])) {
    header('Location: form_login.php');
    exit();
}

// Проверка роли пользователя
if ($_SESSION['role'] !== 'owner') {
    echo "Доступ запрещен";
    exit();
}

// Код для панели владельца
echo "Добро пожаловать, владелец!";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Панель владельца</title>
</head>
<body>
    <h1>Панель владельца</h1>
    <p>Здесь вы можете удалять и добавлять администраторов, продавцов и видеть все денежные потоки и статистику.</p>
    <!-- Пример функции, доступной только владельцу -->
    <a href="manage_staff.php">Управление персоналом</a>
    <a href="view_finances.php">Просмотр финансов</a>
    <a href="logout.php">Выйти</a>
</body>
</html>
