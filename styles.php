<?php
header("Content-Type: text/css");
?>

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

header {
    background-color: #4CAF50;
    color: white;
    padding: 1em;
    text-align: center;
}

.product-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 1em;
}

.product {
    border: 1px solid #ddd;
    padding: 1em;
    margin: 1em;
    text-align: center;
    width: 200px;
}

.product img {
    max-width: 100%;
    height: auto;
}

