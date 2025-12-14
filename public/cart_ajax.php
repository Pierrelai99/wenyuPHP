<?php
session_start();
header('Content-Type: application/json');

// Basic validation
if (!isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'msg' => 'Missing product ID']);
    exit;
}

$id    = $_POST['product_id'];
$name  = $_POST['name'] ?? '';
$price = floatval($_POST['price'] ?? 0);
$image = $_POST['image'] ?? '';

// Init cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or update item
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += 1;
} else {
    $_SESSION['cart'][$id] = [
        'id'    => $id,
        'name'  => $name,
        'price' => $price,
        'image' => $image,
        'qty'   => 1
    ];
}

// Update cart count
$cart_count = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_count += $item['qty'];
}
$_SESSION['cart_count'] = $cart_count;

// Return JSON
echo json_encode([
    'success'    => true,
    'cart_count'=> $cart_count
]);
exit;
