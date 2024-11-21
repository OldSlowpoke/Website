<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $id_tovara = $_POST['id_tovara'];
        $kol_vo = $_POST['kol-vo'];
        $stoimost = $_POST['stoimost'];

        if (!isset($_SESSION['basket'])) {
            $_SESSION['basket'] = [];
        }

        // Проверка, есть ли уже такой товар в корзине
        $found = false;
        foreach ($_SESSION['basket'] as &$item) {
            if ($item['id_tovara'] == $id_tovara) {
                $item['kol-vo'] += $kol_vo;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['basket'][] = [
                'id_tovara' => $id_tovara,
                'kol-vo' => $kol_vo,
                'stoimost' => $stoimost
            ];
        }

        echo "Товар добавлен в корзину";
    } elseif (isset($_POST['action']) && $_POST['action'] == 'remove') {
        $id_tovara = $_POST['id_tovara'];

        foreach ($_SESSION['basket'] as $key => $item) {
            if ($item['id_tovara'] == $id_tovara) {
                unset($_SESSION['basket'][$key]);
                $_SESSION['basket'] = array_values($_SESSION['basket']); // Переиндексация массива
                echo "Товар удален из корзины";
                exit;
            }
        }

        echo "Товар не найден в корзине";
    }
}
?>
