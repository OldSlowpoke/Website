<?php
include 'connect.php';

// SQL-запрос для получения данных о товарах
$sql = "SELECT ProductID, Name, Description, Image, Price FROM Products";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    // Перебор результатов запроса и добавление их в массив
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Возвращение данных в формате JSON
echo json_encode($products);
$conn->close();
?>
