<?php
session_start();
// Начало сессии для отслеживания состояния пользователя
include 'connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.php">
    <title>Главная страница</title>
</head>
<body>
    <h1>Добро пожаловать в нашу систему</h1>
    <?php if (!isset($_SESSION['login'])): ?>
        <!-- Если пользователь не вошел в систему, отображаем кнопки регистрации и входа -->
        <a href="form_registration.php">Регистрация</a>
        <a href="form_login.php">Вход</a>
    <?php else: ?>
        <!-- Если пользователь вошел в систему, отображаем приветственное сообщение и ссылку на личный кабинет -->
        <?php
        $role = $_SESSION['role']; // Получаем роль пользователя из сессии
        $dashboardLink = ''; // Инициализируем переменную для ссылки на личный кабинет

        // Определяем ссылку на личный кабинет в зависимости от роли пользователя
        switch ($role) {
            case 'user':
                $dashboardLink = 'user_panel.php';
                break;
            case 'admin':
                $dashboardLink = 'admin_panel.php';
                break;
            case 'seller':
                $dashboardLink = 'seller_panel.php';
                break;
            case 'owner':
                $dashboardLink = 'owner_panel.php';
                break;
        }
        ?>
        <h1>Добро пожаловать, <?php echo $_SESSION['login']; ?>!</h1>
        <a href="<?php echo $dashboardLink; ?>">Перейти в личный кабинет</a>
        
        
        
    <?php endif; ?>
    <!-- Если пользователь имеет роль 'user', отображаем его корзину -->
    <h2>Ваша корзина:</h2>
    <div id="basket-list"></div>
    <section class="product-list" id="product-list">
        <!-- Товары будут добавлены здесь с помощью JavaScript -->
    </section>
    <script src="scripts.js"></script>
</body>
</html>
