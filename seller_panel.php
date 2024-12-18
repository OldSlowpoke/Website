<?php
session_start();
// Начало сессии для отслеживания состояния пользователя

// Проверка, вошел ли пользователь в систему и является ли он продавцом
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'seller') {
    // Если пользователь не вошел в систему или не является продавцом, перенаправляем его на главную страницу
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = $_SESSION['login'];
$password = "";
$dbname = "shop";
// Создаем соединение с базой данных
$conn = new mysqli($servername, $username, $password, $dbname);
// Проверяем соединение
if ($conn->connect_error) {
    // Если соединение не удалось, выводим сообщение об ошибке и завершаем выполнение скрипта
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных о заказах из базы данных
$query = "SELECT * FROM `Orders`";
$result = $conn->query($query);
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Обработка POST-запроса для обновления статуса заказа
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    $query = "UPDATE `Orders` SET `Status` = '$new_status' WHERE `OrderID` = $order_id";
    if ($conn->query($query) === TRUE) {
        // Если обновление прошло успешно, устанавливаем сообщение об успехе
        $message = "Статус заказа обновлен";
    } else {
        // Если обновление не удалось, устанавливаем сообщение об ошибке
        $message = "Ошибка: " . $query . "<br>" . $conn->error;
    }
}

// Получение имени продавца из базы данных
$sql = "SELECT Name FROM user WHERE login='$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Если найден продавец, получаем его имя
    $row = $result->fetch_assoc();
    $seller_name = $row['Name'];
} else {
    // Если продавец не найден, устанавливаем имя по умолчанию
    $seller_name = "Неизвестный продавец";
}

// Обработка POST-запроса для получения данных товара
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['get_product'])) {
    $product_id_or_name = $_POST['product_id_or_name'];
    $query = "SELECT * FROM products WHERE id = '$product_id_or_name' OR name = '$product_id_or_name'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $product_data = $result->fetch_assoc();
    } else {
        $product_data = null;
    }
}

$conn->close();
// Закрытие соединения с базой данных
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
    <!-- Отображение сообщения, если оно существует -->
    <div>
        <h2>Ввод нового товара</h2>
        <!-- Форма для добавления нового товара -->
        <form action="seller_process.php" method="post">
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="description">Описание:</label>
            <textarea id="description" name="description"></textarea><br>
            <label for="price">Цена:</label>
            <input type="number" step="0.01" id="price" name="price" required><br>
            <button type="submit" name="add_product">Добавить товар</button>
        </form>
    </div>
    <div>
        <h2>Коррекция данных товара</h2>
        <!-- Форма для получения данных товара -->
        <form action="" method="post">
            <label for="product_id_or_name">ID или название товара:</label>
            <input type="text" id="product_id_or_name" name="product_id_or_name" required><br>
            <button type="submit" name="get_product">Получить данные товара</button>
        </form>
        <?php if (isset($product_data)): ?>
            <!-- Форма для обновления данных товара -->
            <form action="seller_process.php" method="post">
                <input type="hidden" name="edit_id" value="<?php echo $product_data['id']; ?>">
                <label for="edit_name">Название:</label>
                <input type="text" id="edit_name" name="edit_name" value="<?php echo $product_data['name']; ?>"><br>
                <label for="edit_description">Описание:</label>
                <textarea id="edit_description" name="edit_description"><?php echo $product_data['description']; ?></textarea><br>
                <label for="edit_price">Цена:</label>
                <input type="number" step="0.01" id="edit_price" name="edit_price" value="<?php echo $product_data['price']; ?>"><br>
                <button type="submit" name="edit_product">Обновить товар</button>
            </form>
        <?php endif; ?>
    </div>
    <div>
        <h2>Удаление товара</h2>
        <!-- Форма для удаления товара -->
        <form action="seller_process.php" method="post">
            <label for="delete_id">ID товара:</label>
            <input type="number" id="delete_id" name="delete_id" required><br>
            <button type="submit" name="delete_product">Удалить товар</button>
        </form>
    </div>
    <div>
        <h2>Личные данные продавца</h2>
        <!-- Отображение личных данных продавца -->
        <p>Имя: <?php echo htmlspecialchars($seller_name); ?></p>
        <p>Текущая дата: <span id="current_date"></span></p>
    </div>
    <div>
        <h2>Заказы</h2>
        <!-- Таблица заказов -->
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
                        <!-- Форма для обновления статуса заказа -->
                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['OrderID']; ?>">
                            <select name="new_status">
                                <option value="ожидает подтверждения" <?php if ($order['Status'] == 'ожидает подтверждения') echo 'selected'; ?>>Ожидает подтверждения</option>
                                <option value="подтвержден" <?php if ($order['Status'] == 'подтвержден') echo 'selected'; ?>>Подтвержден</option>
                                <option value="отменен" <?php if ($order['Status'] == 'отменен') echo 'selected'; ?>>Отменен</option>
                            </select>
                            <button type="submit">Обновить статус</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <!-- Форма для выхода из системы -->
        <form action="logout.php" method="post">
            <button type="submit">Выход</button>
        </form>
    </div>
    <script>
        // Установка текущей даты в элемент с id "current_date"
        document.getElementById('current_date').textContent = new Date().toLocaleDateString();
    </script>
</body>
</html>
