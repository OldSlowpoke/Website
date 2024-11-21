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

// Подключение к базе данных
include 'connect.php';

// Получение данных о покупках
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM `Orders` WHERE `UserID` = $user_id";
$result = $conn->query($query);
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Получение личных данных пользователя
$user_query = "SELECT * FROM `User` WHERE `Login` = '{$_SESSION['login']}'";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Пользовательская панель</title>
</head>
<body>
    <h1>Пользовательская панель</h1>
    <p>Сегодняшняя дата: <?php echo date('Y-m-d'); ?></p>
    <p>Личные данные покупателя:</p>
    <p>Имя: <?php echo $user_data['Name']; ?></p>
    <p>Фамилия: <?php echo $user_data['Surname']; ?></p>
    <p>Email: <?php echo $user_data['Login']; ?></p>

    <h2>Покупки</h2>
    <table border="1">
        <tr>
            <th>Название товара</th>
            <th>Количество</th>
            <th>Стоимость</th>
            <th>Статус</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['ProductName']; ?></td>
                <td><?php echo $order['Quantity']; ?></td>
                <td><?php echo $order['Price']; ?></td>
                <td><?php echo $order['Status']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="logout.php">Выйти</a>
</body>
</html>
