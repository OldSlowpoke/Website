<?php
session_start();
include 'connect.php';

if (isset($_SESSION['basket'])) {
    $basket = $_SESSION['basket'];
    $basket_data = [];

    foreach ($basket as $item) {
        $product_id = $item['id_tovara'];
        $query = "SELECT Name FROM Products WHERE ProductID = $product_id";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $product_name = $result->fetch_assoc()['Name'];
        } else {
            $product_name = 'Неизвестный товар';
        }

        $basket_data[] = [
            'id_tovara' => $item['id_tovara'],
            'name' => $product_name,
            'kol_vo' => $item['kol-vo'],
            'stoimost' => $item['stoimost']
        ];
    }

    echo json_encode($basket_data);
} else {
    echo json_encode([]);
}
?>
