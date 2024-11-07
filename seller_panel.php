<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['login'])) {
    header('Location: form_login.php');
    exit();
}

// Проверка роли пользователя
if ($_SESSION['role'] !== 'seller') {
    echo "Доступ запрещен";
    exit();
}

// Код для панели продавца
echo "Добро пожаловать, продавец!";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Панель продавца</title>
</head>
<body>
    <h1>Панель продавца</h1>
    <p>Здесь вы можете добавлять новые позиции в магазин, регулировать заказы и видеть данные заказов и клиентов.</p>
    <!-- Пример функции, доступной только продавцам -->
    <a href="manage_orders.php">Управление заказами</a>
    <a href="add_product.php">Добавить новую позицию</a>
    <a href="logout.php">Выйти</a>
</body>
</html>
