<?php
session_start();
include 'connect.php';

if (isset($_SESSION['basket'])) {
    $basket = $_SESSION['basket'];
    $basket_data = [];

    foreach ($basket as $item) {
        $product_id = $item['id_tovara'];
        $query = "SELECT name FROM Products WHERE id = $product_id";
        $result = $conn->query($query);
        $product_name = $result->fetch_assoc()['name'];

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
