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

// Подключение к базе данных
include 'connect.php';

// Получение личных данных владельца
$owner_query = "SELECT * FROM users WHERE login = '{$_SESSION['login']}'";
$owner_result = $conn->query($owner_query);
$owner_data = $owner_result->fetch_assoc();

// Обработка запроса на получение прибыли
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['get_profit'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Расчет общей прибыли
    $sales_query = "
        SELECT total_amount AS total_sales
        FROM orders
        WHERE order_type = 'продажа' AND order_date BETWEEN '$start_date' AND '$end_date'
    ";
    $sales_result = $conn->query($sales_query);
    $sales_data = $sales_result->fetch_assoc();
    $total_sales = $sales_data['total_sales'];

    $purchases_query = "
        SELECT total_amount AS total_purchases
        FROM orders
        WHERE order_type = 'закупка' AND order_date BETWEEN '$start_date' AND '$end_date'
    ";
    $purchases_result = $conn->query($purchases_query);
    $purchases_data = $purchases_result->fetch_assoc();
    $total_purchases = $purchases_data['total_purchases'];

    $total_profit = $total_sales - $total_purchases; // Прибыль
}

// Обработка запроса на получение рейтинга товаров
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['get_rating'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Расчет рейтинга товаров
    $rating_query = "SELECT p.id, p.name,
       SUM(CASE WHEN o.order_type = 'продажа' THEN oi.quantity * oi.price
                WHEN o.order_type = 'закупка' THEN -oi.quantity * oi.price
           END) AS total_profit
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.order_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY p.id, p.name
        ORDER BY total_profit DESC
        LIMIT 3
    ";
    $rating_result = $conn->query($rating_query);
    $rating_data = $rating_result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель владельца</title>
</head>
<body>
    <h1>Панель владельца</h1>
    <p>Здесь вы можете удалять и добавлять администраторов, продавцов и видеть все денежные потоки и статистику.</p>

    <!-- Личные данные владельца -->
    <h2>Личные данные владельца</h2>
    <p>Имя: <?php echo htmlspecialchars($owner_data['name']); ?></p>
    <p>Email: <?php echo htmlspecialchars($owner_data['login']); ?></p>

    <!-- Текущая дата -->
    <p>Текущая дата: <?php echo date('Y-m-d'); ?></p>

    <!-- Получение информации о полученной прибыли -->
    <h2>Получение информации о полученной прибыли в интервале</h2>
    <form action="" method="post">
        <label for="start_date">Ввод даты начала:</label>
        <input type="date" id="start_date" name="start_date" required><br>
        <label for="end_date">Ввод даты окончания:</label>
        <input type="date" id="end_date" name="end_date" required><br>
        <button type="submit" name="get_profit">Получить прибыль</button>
    </form>
    <?php if (isset($total_profit)): ?>
        <p>Общая прибыль за выбранный период: <?php echo $total_profit; ?> руб.</p>
    <?php endif; ?>

    <!-- Получение информации о рейтинге товаров -->
    <h2>Получение информации о рейтинге товаров, приносящих максимальную прибыль</h2>
    <form action="" method="post">
        <label for="start_date">Ввод даты начала:</label>
        <input type="date" id="start_date" name="start_date" required><br>
        <label for="end_date">Ввод даты окончания:</label>
        <input type="date" id="end_date" name="end_date" required><br>
        <button type="submit" name="get_rating">Получить рейтинг</button>
    </form>
    <?php if (isset($rating_data)): ?>
        <h3>Рейтинг товаров:</h3>
        <ul>
            <?php foreach ($rating_data as $item): ?>
                <li><?php echo $item['name']; ?> - <?php echo $item['total_profit']; ?> руб.</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Кнопка выхода -->
    <form action="logout.php" method="post">
        <button type="submit">Выйти</button>
    </form>
</body>
</html>
