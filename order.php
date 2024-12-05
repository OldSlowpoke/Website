<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Проверка наличия корзины в сессии
    if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
        $basket = $_SESSION['basket'];
        $user_id = $_SESSION['user_id'];
        $total_amount = 0; // Инициализация общей суммы заказа

        foreach ($basket as $item) {
            $product_id = $item['id_tovara'];
            $quantity = $item['kol-vo'];
            $price = $item['stoimost'];
            $status = 'ожидает подтверждения';

            // Получение названия товара
            $product_query = "SELECT name FROM Products WHERE id = $product_id";
            $product_result = $conn->query($product_query);
            $product_name = $product_result->fetch_assoc()['name'];

            // Вставка заказа в базу данных
            $query = "INSERT INTO `Orders` (`UserID`, `ProductID`, `ProductName`, `Quantity`, `Price`, `Status`) VALUES ($user_id, $product_id, '$product_name', $quantity, $price, '$status')";
            if ($conn->query($query) !== TRUE) {
                echo "Ошибка: " . $query . "<br>" . $conn->error;
                exit;
            }

            // Добавление стоимости товара к общей сумме заказа
            $total_amount += $quantity * $price;
        }

        // Запись общей суммы заказа в базу данных
        $order_id = $conn->insert_id; // Получение ID последнего вставленного заказа
        $update_query = "UPDATE `Orders` SET `TotalAmount` = $total_amount WHERE `OrderID` = $order_id";
        if ($conn->query($update_query) !== TRUE) {
            echo "Ошибка: " . $update_query . "<br>" . $conn->error;
            exit;
        }

        echo "Заказ оформлен и ожидает подтверждения";
        // Очистка корзины после оформления заказа
        unset($_SESSION['basket']);
    } else {
        echo "Корзина пуста. Добавьте товары перед оформлением заказа.";
    }
}
?>
