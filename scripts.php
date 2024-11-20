<?php
header("Content-Type: application/javascript");
?>

document.addEventListener('DOMContentLoaded', function() {
    // Запрос к серверу для получения данных о товарах
    fetch('get_products.php')
        .then(response => response.json())
        .then(data => {
            const productList = document.getElementById('product-list');
            data.forEach(product => {
                // Создание элемента для каждого товара
                const productDiv = document.createElement('div');
                productDiv.className = 'product';
                productDiv.innerHTML = `
                    <img src="${product.image}" alt="${product.name}">
                    <h2>${product.name}</h2>
                    <p>${product.description}</p>
                    <p>Цена: ${product.price} руб.</p>
                    <label for="quantity${product.id}">Количество:</label>
                    <input type="number" id="quantity${product.id}" name="quantity${product.id}" min="1" value="1">
                    <button onclick="addToCart(${product.id})">Перейти в корзину</button>
                `;
                productList.appendChild(productDiv);
            });
        })
        .catch(error => console.error('Ошибка:', error));
});

function addToCart(productId) {
    const quantity = document.getElementById(`quantity${productId}`).value;
    alert(`Товар ${productId} добавлен в корзину в количестве ${quantity}`);
    // Здесь можно добавить логику для отправки данных на сервер
}

