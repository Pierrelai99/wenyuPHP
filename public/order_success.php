<?php
session_start();

$order_no = $_GET['order_no'] ?? 'N/A';

$page_title = "Order Successful";
include '../includes/header.php';
?>

<section class="order-success">
    <div class="container">
        <h1>Order Successful!</h1>
        <p>Your order has been placed successfully.</p>
        <p><strong>Order Number:</strong> <?= htmlspecialchars($order_no) ?></p>
        <a href="products.php" class="btn btn-primary">Continue Shopping</a>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
