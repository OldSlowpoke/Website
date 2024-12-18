<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Проверка наличия корзины в сессии
    if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
        $basket = $_SESSION['basket'];
        $user_id = $_SESSION['user_id'];
        $total_amount = 0; // Инициализация общей суммы заказа

        // Начинаем транзакцию
        $conn->begin_transaction();

        try {
            // Вставка нового заказа в таблицу Orders
            $order_query = "INSERT INTO `Orders` (`UserID`, `TotalAmount`, `Status`) VALUES (?, NULL, ?)";
            $stmt = $conn->prepare($order_query);
            $status = 'ожидает подтверждения';
            $stmt->bind_param("is", $user_id, $status);
            $stmt->execute();
            $order_id = $conn->insert_id; // Получение ID только что созданного заказа

            foreach ($basket as $item) {
                $product_id = $item['id_tovara'];
                $quantity = $item['kol-vo'];
                $price = $item['stoimost'];

                // Вставка деталей заказа в таблицу OrderItems
                $item_query = "INSERT INTO `OrderItems` (`OrderID`, `ProductID`, `Quantity`, `Price`) 
                               VALUES (?, ?, ?, ?)";
                $item_stmt = $conn->prepare($item_query);
                $item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                $item_stmt->execute();

                // Добавление стоимости товара к общей сумме заказа
                $total_amount += $quantity * $price;
            }

            // Обновление общей суммы заказа в таблице Orders
            $update_query = "UPDATE `Orders` SET `TotalAmount` = ? WHERE `OrderID` = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("di", $total_amount, $order_id);
            $update_stmt->execute();

            // Фиксируем транзакцию
            $conn->commit();
            echo "Заказ оформлен и ожидает подтверждения";

            // Очистка корзины после оформления заказа
            unset($_SESSION['basket']);
        } catch (Exception $e) {
            // В случае ошибки откатываем все изменения
            $conn->rollback();
            echo "Ошибка: " . $e->getMessage();
        }
    } else {
        echo "Корзина пуста. Добавьте товары перед оформлением заказа.";
    }
}
?>