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

// Код для пользовательской панели
echo "Добро пожаловать, пользователь!";
?>
