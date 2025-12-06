<?php
session_start();

if (!isset($_POST['product_id'])) {
    header("Location: products.php");
    exit;
}

$id = $_POST['product_id'];
$name = $_POST['name'];
$price = floatval($_POST['price']);
$image = $_POST['image'];
$sku = $_POST['sku'];

// If cart not created yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// If item already exists, increase quantity
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += 1;
} else {
    // New item
    $_SESSION['cart'][$id] = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'image' => $image,
        'sku' => $sku,
        'qty' => 1
    ];
}

header("Location: cart.php");
exit;
