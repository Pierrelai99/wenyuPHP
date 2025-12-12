<?php
session_start();
require_once '../includes/db.php';

// 1. MUST BE LOGGED IN
if (!isset($_SESSION['user_code'])) {
    header("Location: login.php");
    exit;
}


$user_code = $_SESSION['user_code'];


// 2. CART MUST NOT BE EMPTY
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Calculate total
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['qty'];
}

// When form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $payment_method = $_POST['payment_method'];
    $shipping_address = $_POST['shipping_address'];
    $billing_address = $_POST['billing_address'];

    // Generate order number
    $order_no = "ORD" . date("YmdHis");

    // Insert main order
    $sql = "INSERT INTO seafood_orders 
            (user_code, order_no, total_price, shipping_address, billing_address, payment_method, payment_status)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $user_code,
        $order_no,
        $total_price,
        $shipping_address,
        $billing_address,
        $payment_method,
        "paid"  // simulate successful payment
    ]);

    // Get auto-generated order_id
    $order_id = $pdo->lastInsertId();


    // -------------------------------------------------
    // INSERT ORDER ITEMS (NEW LOGIC)
    // -------------------------------------------------
    $item_sql = "
        INSERT INTO seafood_order_items 
        (order_id, product_id, product_name, unit_price, quantity, subtotal, product_image)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
    $item_stmt = $pdo->prepare($item_sql);

    foreach ($_SESSION['cart'] as $item) {

        $product_id = $item['id'];
        $name = $item['name'];
        $unit_price = $item['price'];
        $qty = $item['qty'];
        $subtotal = $unit_price * $qty;

        // Fix image path to store a clean path
        $img_path = ltrim($item['image'], '/');

        $item_stmt->execute([
            $order_id,
            $product_id,
            $name,
            $unit_price,
            $qty,
            $subtotal,
            $img_path
        ]);
    }


    // -------------------------------------------------
    // CLEAR CART AFTER CHECKOUT
    // -------------------------------------------------
    unset($_SESSION['cart']);
    unset($_SESSION['cart_count']);


    // Redirect to success page
    header("Location: order_success.php?order_no=$order_no");
    exit;
}


// page UI
$page_title = "Checkout";
include '../includes/header.php';
?>

<section class="checkout-page">
    <div class="container">
        <h1>Checkout</h1>

        <div class="checkout-box">

            <h3>Order Summary</h3>
            <p><strong>Total Price:</strong> RM<?= number_format($total_price, 2) ?></p>

            <form method="POST">

                <h3>Shipping Address</h3>
                <textarea name="shipping_address" required class="form-control" rows="3"></textarea>

                <h3>Billing Address</h3>
                <textarea name="billing_address" required class="form-control" rows="3"></textarea>

                <h3>Payment Method</h3>
                <label>
                    <input type="radio" name="payment_method" value="tng" required>
                    Touch 'n Go eWallet
                </label><br>

                <label>
                    <input type="radio" name="payment_method" value="online_banking" required>
                    Online Banking (FPX)
                </label>

                <br><br>
                <button class="btn btn-primary">Confirm & Create Order</button>
            </form>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
