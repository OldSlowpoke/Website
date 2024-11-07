<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['login'])) {
    header('Location: form_login.php');
    exit();
}

// Проверка роли пользователя
if ($_SESSION['role'] !== 'admin') {
    echo "Доступ запрещен";
    exit();
}

// Код для административной панели
echo "Добро пожаловать, администратор!";
?>
