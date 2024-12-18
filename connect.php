<?php
$servername = "localhost"; // Имя сервера
$username = "new_user"; // Имя пользователя базы данных
$password = "password"; // Пароль пользователя базы данных
$dbname = "its_database"; // Имя базы данных

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
