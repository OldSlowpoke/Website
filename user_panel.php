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

// Получение данных о заказах
$user_id = $_SESSION['user_id'];
$query = "
    SELECT o.OrderID, o.TotalAmount, o.Status, oi.ProductID, p.Name AS ProductName, oi.Quantity
    FROM Orders o
    JOIN OrderItems oi ON o.OrderID = oi.OrderID
    JOIN Products p ON oi.ProductID = p.ProductID
    WHERE o.UserID = $user_id
";
$result = $conn->query($query);
$orders = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row; // Сохраняем каждую строку в массив
    }
}

// Получение личных данных пользователя
$user_query = "SELECT * FROM `Users` WHERE `Login` = '{$_SESSION['login']}'";
$user_result = $conn->query($user_query);
if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
} else {
    echo "Пользователь не найден.";
    exit();
}

// Получение данных из корзины
$basket = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];
$total_amount = 0; // Инициализация общей суммы заказа

foreach ($basket as $item) {
    $total_amount += $item['stoimost'] * $item['kol-vo']; // Рассчитываем общую стоимость
}
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

    <h2>Корзина</h2>
    <div id="basket-list">
        <?php if (empty($basket)): ?>
            <p>Корзина пуста.</p>
        <?php else: ?>
            <table border="1">
                <tr>
                    <th>Название товара</th>
                    <th>Количество</th>
                    <th>Стоимость</th>
                </tr>
                <?php foreach ($basket as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['kol-vo']); ?></td>
                        <td><?php echo htmlspecialchars($item['stoimost']); ?> руб.</td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p>Общая стоимость: <?php echo htmlspecialchars($total_amount); ?> руб.</p>
            <form action="order.php" method="POST">
                <button type="submit">Оформить заказ</button>
            </form>
        <?php endif; ?>
    </div>

    <h2>Покупки</h2>
    <table border="1">
        <tr>
            <th>ID заказа</th>
            <th>Название товара</th>
            <th>Количество</th>
            <th>Общая стоимость</th>
            <th>Статус</th>
            <th>Действие</th>
        </tr>
        <?php
        $current_order_id = null;
        $current_total_amount = 0;
        foreach ($orders as $order): 
            if ($current_order_id !== $order['OrderID']): // Если новый заказ
                if ($current_order_id !== null): // Если это не первый заказ, выводим итог
                    echo "<tr>
                            <td>{$current_order_id}</td>
                            <td colspan='2'>Итого</td>
                            <td>{$current_total_amount} руб.</td>
                            <td>{$order['Status']}</td>
                            <td>
                                <form action='cancel_order.php' method='POST'>
                                    <input type='hidden' name='order_id' value='{$current_order_id}'>
                                    <button type='submit'>Отменить заказ</button>
                                </form>
                            </td>
                          </tr>";
                endif;
                // Сброс значений для нового заказа
                $current_order_id = $order['OrderID'];
                $current_total_amount = $order['TotalAmount'];
            endif;
            // Выводим товары текущего заказа
            if ($current_order_id === $order['OrderID']) {
                echo "<tr>
                        <td></td>
                        <td>{$order['ProductName']}</td>
                        <td>{$order['Quantity']}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>";
            }
        endforeach;
        // Выводим итог для последнего заказа
        if ($current_order_id !== null) {
            echo "<tr>
                    <td>{$current_order_id}</td>
                    <td colspan='2'>Итого</td>
                    <td>{$current_total_amount} руб.</td>
                    <td>{$order['Status']}</td>
                    <td>
                        <form action='cancel_order.php' method='POST'>
                            <input type='hidden' name='order_id' value='{$current_order_id}'>
                            <button type='submit'>Отменить заказ</button>
                        </form>
                    </td>
                  </tr>";
        }
        ?>
    </table>

    <a href="index.php">На главную</a>
    <a href="logout.php">Выйти</a>
</body>
</html>
