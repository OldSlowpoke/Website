<?php
header("Content-Type: application/javascript");
// Устанавливаем заголовок для отправки JavaScript кода
?>

document.addEventListener('DOMContentLoaded', function() {
    // Обработчик события загрузки DOM
    // Этот обработчик выполняется после полной загрузки HTML-документа

    // Запрос к серверу для получения данных о товарах
    fetch('get_products.php')
        .then(response => response.json())
        // Преобразование ответа сервера в формат JSON
        .then(data => {
            // Получаем элемент, в который будут добавлены товары
            const productList = document.getElementById('product-list');
            // Перебираем каждый товар в полученных данных
            data.forEach(product => {
                // Создание элемента для каждого товара
                const productDiv = document.createElement('div');
                productDiv.className = 'product';
                // Вставка HTML-кода для отображения товара
                productDiv.innerHTML = `
                    <img src="${product.image}" alt="${product.name}">
                    <h2>${product.name}</h2>
                    <p>${product.description}</p>
                    <p>Цена: ${product.price} руб.</p>
                    <label for="quantity${product.id}">Количество:</label>
                    <input type="number" id="quantity${product.id}" name="quantity${product.id}" min="1" value="1">
                    <button onclick="addToCart(${product.id}, ${product.price})">Добавить в корзину</button>
                `;
                // Добавление элемента товара в список товаров
                productList.appendChild(productDiv);
            });
        })
        // Обработка ошибок при запросе данных о товарах
        .catch(error => console.error('Ошибка:', error));

    // Загрузка корзины
    fetch('get_basket.php')
        .then(response => response.json())
        // Преобразование ответа сервера в формат JSON
        .then(data => {
            // Получаем элемент, в который будут добавлены товары из корзины
            const basketList = document.getElementById('basket-list');
            if (basketList) {
                basketList.innerHTML = ''; // Очистка корзины перед заполнением
                // Перебираем каждый товар в корзине
                data.forEach(item => {
                    const basketItem = document.createElement('div');
                    basketItem.className = 'basket-item';
                    // Вставка HTML-кода для отображения товара в корзине
                    basketItem.innerHTML = `
                        <p>${item.name} - ${item.kol_vo} x ${item.stoimost} руб.</p>
                        <button onclick="removeFromCart(${item.id_tovara})">Удалить из корзины</button>
                    `;
                    // Добавление элемента товара в корзину
                    basketList.appendChild(basketItem);
                });
            }
        })
        // Обработка ошибок при загрузке корзины
        .catch(error => console.error('Ошибка:', error));
});

// Функция для добавления товара в корзину
function addToCart(productId, productPrice) {
    // Получаем количество выбранного товара
    const quantity = document.getElementById(`quantity${productId}`).value;
    // Отправка запроса на сервер для добавления товара в корзину
    fetch('basket.php', {
        method: 'POST', // Метод запроса POST
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Тип содержимого запроса
        },
        // Формирование тела запроса с данными о товаре
        body: `action=add&id_tovara=${productId}&kol-vo=${quantity}&stoimost=${productPrice}`
    })
    .then(response => response.text())
    // Обработка ответа от сервера
    .then(data => {
        console.log('Success:', data);
        alert(`Товар ${productId} добавлен в корзину в количестве ${quantity}`);
        // Обновление корзины после добавления товара
        fetch('get_basket.php')
            .then(response => response.json())
            // Преобразование ответа сервера в формат JSON
            .then(data => {
                const basketList = document.getElementById('basket-list');
                if (basketList) {
                    basketList.innerHTML = ''; // Очистка корзины перед заполнением
                    // Перебираем каждый товар в корзине
                    data.forEach(item => {
                        const basketItem = document.createElement('div');
                        basketItem.className = 'basket-item';
                        // Вставка HTML-кода для отображения товара в корзине
                        basketItem.innerHTML = `
                            <p>${item.name} - ${item.kol_vo} x ${item.stoimost} руб.</p>
                            <button onclick="removeFromCart(${item.id_tovara})">Удалить из корзины</button>
                        `;
                        // Добавление элемента товара в корзину
                        basketList.appendChild(basketItem);
                    });
                }
            })
            // Обработка ошибок при обновлении корзины
            .catch(error => console.error('Ошибка:', error));
    })
    // Обработка ошибок при добавлении товара в корзину
    .catch((error) => {
        console.error('Error:', error);
    });
}

// Функция для удаления товара из корзины
function removeFromCart(productId) {
    // Отправка запроса на сервер для удаления товара из корзины
    fetch('basket.php', {
        method: 'POST', // Метод запроса POST
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Тип содержимого запроса
        },
        // Формирование тела запроса с данными о товаре
        body: `action=remove&id_tovara=${productId}`
    })
    .then(response => response.text())
    // Обработка ответа от сервера
    .then(data => {
        console.log('Success:', data);
        alert(`Товар ${productId} удален из корзины`);
        // Обновление корзины после удаления товара
        fetch('get_basket.php')
            .then(response => response.json())
            // Преобразование ответа сервера в формат JSON
            .then(data => {
                const basketList = document.getElementById('basket-list');
                if (basketList) {
                    basketList.innerHTML = ''; // Очистка корзины перед заполнением
                    // Перебираем каждый товар в корзине
                    data.forEach(item => {
                        const basketItem = document.createElement('div');
                        basketItem.className = 'basket-item';
                        // Вставка HTML-кода для отображения товара в корзине
                        basketItem.innerHTML = `
                            <p>${item.name} - ${item.kol_vo} x ${item.stoimost} руб.</p>
                            <button onclick="removeFromCart(${item.id_tovara})">Удалить из корзины</button>
                        `;
                        // Добавление элемента товара в корзину
                        basketList.appendChild(basketItem);
                    });
                }
            })
            // Обработка ошибок при обновлении корзины
            .catch(error => console.error('Ошибка:', error));
    })
    // Обработка ошибок при удалении товара из корзины
    .catch((error) => {
        console.error('Error:', error);
    });
}
