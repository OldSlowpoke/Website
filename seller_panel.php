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
        <form action="process.php" method="post">
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
        <form action="process.php" method="post">
            <label for="edit_id">ID товара:</label>
            <input type="number" id="edit_id" name="edit_id" required><br>
            <label for="edit_name">Название:</label>
            <input type="text" id="edit_name" name="edit_name"><br>
            <label for="edit_description">Описание:</label>
            <textarea id="edit_description" name="edit_description"></textarea><br>
            <label for="edit_price">Цена:</label>
            <input type="number" step="0.01" id="edit_price" name="edit_price"><br>
            <button type="submit" name="edit_product">Обновить товар</button>
        </form>
    </div>
    <div>
        <h2>Удаление товара</h2>
        <form action="process.php" method="post">
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
        <button onclick="window.location.href='index.php'">Выход</button>
    </div>
    <script>
        document.getElementById('current_date').textContent = new Date().toLocaleDateString();
    </script>
</body>
</html>
