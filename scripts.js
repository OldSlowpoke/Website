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

                // Создание элементов для товара
                const img = document.createElement('img');
                img.src = product.Image;
                img.alt = product.Name;

                const name = document.createElement('h2');
                name.textContent = product.Name;

                const description = document.createElement('p');
                description.textContent = product.Description;

                const price = document.createElement('p');
                price.textContent = `Цена: ${product.Price} руб.`;

                const label = document.createElement('label');
                label.textContent = 'Количество:';
                label.htmlFor = `quantity${product.ProductID}`;

                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.id = `quantity${product.ProductID}`;
                quantityInput.name = `quantity${product.ProductID}`;
                quantityInput.min = '1';
                quantityInput.value = '1';

                const addButton = document.createElement('button');
                addButton.textContent = 'Добавить в корзину';
                addButton.addEventListener('click', () => addToCart(product.ProductID, product.Price));

                // Добавление элементов в productDiv
                productDiv.appendChild(img);
                productDiv.appendChild(name);
                productDiv.appendChild(description);
                productDiv.appendChild(price);
                productDiv.appendChild(label);
                productDiv.appendChild(quantityInput);
                productDiv.appendChild(addButton);

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

                    const itemText = document.createElement('p');
                    itemText.textContent = `${item.name} - ${item.kol_vo} x ${item.stoimost} руб.`;

                    const removeButton = document.createElement('button');
                    removeButton.textContent = 'Удалить из корзины';
                    removeButton.addEventListener('click', () => removeFromCart(item.id_tovara));

                    // Добавление элементов в basketItem
                    basketItem.appendChild(itemText);
                    basketItem.appendChild(removeButton);

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

                        const itemText = document.createElement('p');
                        itemText.textContent = `${item.name} - ${item.kol_vo} x ${item.stoimost} руб.`;

                        const removeButton = document.createElement('button');
                        removeButton.textContent = 'Удалить из корзины';
                        removeButton.addEventListener('click', () => removeFromCart(item.id_tovara));

                        // Добавление элементов в basketItem
                        basketItem.appendChild(itemText);
                        basketItem.appendChild(removeButton);

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

                        const itemText = document.createElement('p');
                        itemText.textContent = `${item.name} - ${item.kol_vo} x ${item.stoimost} руб.`;

                        const removeButton = document.createElement('button');
                        removeButton.textContent = 'Удалить из корзины';
                        removeButton.addEventListener('click', () => removeFromCart(item.id_tovara));

                        // Добавление элементов в basketItem
                        basketItem.appendChild(itemText);
                        basketItem.appendChild(removeButton);

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
