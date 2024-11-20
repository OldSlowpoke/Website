<?php
session_start();

// Проверка, вошел ли пользователь в систему и является ли он администратором
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'seller') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = $_SESSION['login'];
$password = "";
$dbname = "shop";

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Обработка добавления нового товара
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "INSERT INTO products (name, description, price) VALUES ('$name', '$description', $price)";

    if ($conn->query($sql) === TRUE) {
        $message = "Новый товар добавлен успешно";
    } else {
        $message = "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

// Обработка коррекции данных товара
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
    $id = $_POST['edit_id'];
    $name = $_POST['edit_name'];
    $description = $_POST['edit_description'];
    $price = $_POST['edit_price'];

    $sql = "UPDATE products SET name='$name', description='$description', price=$price WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $message = "Товар обновлен успешно";
    } else {
        $message = "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

// Обработка удаления товара
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product'])) {
    $id = $_POST['delete_id'];

    $sql = "DELETE FROM products WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $message = "Товар удален успешно";
    } else {
        $message = "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

// Получение имени продавца из базы данных
$sql = "SELECT name FROM sellers WHERE login='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $seller_name = $row['name'];
} else {
    $seller_name = "Неизвестный продавец";
}

$conn->close();

include 'seller_dashboard.php';
?>
