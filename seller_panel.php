<?php
session_start();

// Проверка, вошел ли пользователь в систему и является ли он продавцом
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'seller') {
    header("Location: index.php");
    exit();
}

include 'connect.php'; // Подключаем файл соединения с базой данных

// Получение данных о заказах из базы данных
$query = "SELECT o.OrderID, o.UserID, oi.Quantity, oi.Price, o.Status, p.Name AS ProductName
          FROM Orders o
          JOIN OrderItems oi ON o.OrderID = oi.OrderID
          JOIN Products p ON oi.ProductID = p.ProductID";
$result = $conn->query($query);
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Получение имени продавца из базы данных
$username = $_SESSION['login'];
$sql = "SELECT Name FROM Users WHERE Login='$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $seller_name = $row['Name'];
} else {
    $seller_name = "Неизвестный продавец";
}

// Обработка получения данных товара
$product_data = null; // Инициализация переменной для данных товара
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['get_product'])) {
    $product_id_or_name = $_POST['product_id_or_name'];
    // Поиск товара по ID или имени
    $stmt = $conn->prepare("SELECT * FROM Products WHERE ProductID = ? OR Name = ?");
    $stmt->bind_param("is", $product_id_or_name, $product_id_or_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product_data = $result->fetch_assoc(); // Получаем данные товара
    } else {
        $message = "Товар не найден.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет продавца</title>
</head>
<body>
    <h1>Личный кабинет продавца</h1>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
    <div>
        <h2>Ввод нового товара</h2>
        <form action="seller_process.php" method="post">
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="description">Описание:</label>
            <textarea id="description" name="description"></textarea><br>
            <label for="price">Цена:</label>
            <input type="number" step="0.01" id="price" name="price" required><br>
            <label for="image">URL изображения:</label>
            <input type="text" id="image" name="image" required><br>
            <button type="submit" name="add_product">Добавить товар</button>
        </form>
    </div>
    <div>
        <h2>Коррекция данных товара</h2>
        <form action="seller_panel.php" method="post">
            <label for="product_id_or_name">ID или название товара:</label>
            <input type="text" id="product_id_or_name" name="product_id_or_name" required><br>
            <button type="submit" name="get_product">Получить данные товара</button>
        </form>
        <?php if (isset($product_data)): ?>
            <form action="seller_process.php" method="post">
                <input type="hidden" name="edit_id" value="<?php echo $product_data['ProductID']; ?>">
                <label for="edit_name">Название:</label>
                <input type="text" id="edit_name" name="edit_name" value="<?php echo $product_data['Name']; ?>"><br>
                <label for="edit_description">Описание:</label>
                <textarea id="edit_description" name="edit_description"><?php echo $product_data['Description']; ?></textarea><br>
                <label for="edit_price">Цена:</label>
                <input type="number" step="0.01" id="edit_price" name="edit_price" value="<?php echo $product_data['Price']; ?>"><br>
                <label for="edit_image">URL изображения:</label>
                <input type="text" id="edit_image" name="edit_image" value="<?php echo $product_data['Image']; ?>"><br>
                <button type="submit" name="edit_product">Обновить товар</button>
            </form>
        <?php endif; ?>
    </div>
    <div>
        <h2>Удаление товара</h2>
        <form action="seller_process.php" method="post">
            <label for="delete_id">ID товара:</label>
            <input type="number" id="delete_id" name="delete_id" required><br>
            <button type="submit" name="delete_product">Удалить товар</button>
        </form>
    </div>
    <div>
        <h2>Личные данные продавца</h2>
        <p>Имя: <?php echo htmlspecialchars($seller_name); ?></p>
        <p>Текущая дата: <span id="current_date"></span></p>
    </div>
    <div>
        <h2>Заказы</h2>
        <table border="1">
            <tr>
                <th>ID заказа</th>
                <th>ID пользователя</th>
                <th>Название товара</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Статус</th>
                <th>Действие</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['OrderID']; ?></td>
                    <td><?php echo $order['UserID']; ?></td>
                    <td><?php echo $order['ProductName']; ?></td>
                    <td><?php echo $order['Quantity']; ?></td>
                    <td><?php echo $order['Price']; ?></td>
                    <td><?php echo $order['Status']; ?></td>
                    <td>
                        <form action="seller_process.php" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['OrderID']; ?>">
                            <select name="new_status">
                                <option value="ожидает подтверждения" <?php if ($order['Status'] == 'ожидает подтверждения') echo 'selected'; ?>>Ожидает подтверждения</option>
                                <option value="подтвержден" <?php if ($order['Status'] == 'подтвержден') echo 'selected'; ?>>Подтвержден</option>
                                <option value="отменен" <?php if ($order['Status'] == 'отменен') echo 'selected'; ?>>Отменен</option>
                            </select>
                            <button type="submit" name="update_status">Обновить статус</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <form action="logout.php" method="post">
            <button type="submit">Выход</button>
        </form>
    </div>
    <script>
        document.getElementById('current_date').textContent = new Date().toLocaleDateString();
    </script>
</body>
</html>