<?php
session_start();

// Only logged-in customers can access
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../public/login.php");
    exit();
}

require_once "../includes/db.php";

// Validate order ID
if (!isset($_GET['order'])) {
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['order']);

// Load order (ensure belongs to this user)
$stmt = $pdo->prepare("
    SELECT *
    FROM seafood_orders
    WHERE order_id = ? AND user_code = ?
");
$stmt->execute([$order_id, $_SESSION['user_code']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    $_SESSION['error'] = "Order not found.";
    header("Location: orders.php");
    exit();
}
// Load order items
$item_stmt = $pdo->prepare("
    SELECT item_id, product_id, product_name, unit_price, quantity, subtotal, product_image
    FROM seafood_order_items
    WHERE order_id = ?
");
$item_stmt->execute([$order_id]);
$order_items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);

// Page metadata
$page_title = "Order Details - " . $order['order_no'];
$page_description = "View order details and tracking";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'orders.php', 'title' => 'My Orders'],
    ['url' => '#', 'title' => "Order " . $order['order_no']]
];

// Header
include "../includes/header.php";
?>

<section class="order-details-section">
    <div class="container">

        <h1>Order #<?= htmlspecialchars($order['order_no']); ?></h1>
        <p>Placed on <?= date("d M Y, h:i A", strtotime($order['created_on'])); ?></p>

        <!-- STATUS BADGE -->
        <div class="order-status-box">
            <span class="status-badge status-<?= $order['order_status']; ?>">
                <?= ucfirst(str_replace('_', ' ', $order['order_status'])); ?>
            </span>

            <span class="payment-badge payment-<?= $order['payment_status']; ?>">
                Payment: <?= ucfirst($order['payment_status']); ?>
            </span>
        </div>

        <!-- ORDER SUMMARY -->
        <div class="order-summary">
            <h2>🧾 Order Summary</h2>
            <ul>
                <li><strong>Total Price:</strong> RM <?= number_format($order['total_price'], 2); ?></li>
                <li><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method'] ?: "Not specified"); ?></li>
                <li><strong>Packaging Method:</strong> <?= ucfirst(str_replace('_', ' ', $order['packaging_method'])); ?></li>
                <li><strong>Delivery Type:</strong> <?= ucfirst($order['delivery_type']); ?></li>
                <li><strong>Time Slot:</strong> <?= htmlspecialchars($order['delivery_time_slot'] ?: "Not chosen"); ?></li>
            </ul>
        </div>

        <!-- SHIPPING & BILLING -->
        <div class="address-section">
            <div class="address-card">
                <h3>📦 Shipping Address</h3>
                <p><?= nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
            </div>

            <div class="address-card">
                <h3>💳 Billing Address</h3>
                <p><?= nl2br(htmlspecialchars($order['billing_address'])); ?></p>
            </div>
        </div>

        <!-- NOTES -->
        <?php if (!empty($order['notes'])): ?>
        <div class="order-notes">
            <h3>📝 Notes</h3>
            <p><?= nl2br(htmlspecialchars($order['notes'])); ?></p>
        </div>
        <?php endif; ?>

        <!-- TRACKING TIMELINE -->
        <h2>🚚 Order Tracking</h2>
        <div class="tracking-timeline">

            <div class="step <?= ($order['order_status'] !== 'pending' ? 'completed' : '') ?>">
                <span class="circle"></span>
                <p>Order Placed</p>
            </div>

            <div class="step <?= ($order['order_status'] === 'preparing' || $order['order_status'] === 'packed' || $order['order_status'] === 'out_for_delivery' || $order['order_status'] === 'delivered') ? 'completed' : '' ?>">
                <span class="circle"></span>
                <p>Preparing</p>
            </div>

            <div class="step <?= ($order['order_status'] === 'packed' || $order['order_status'] === 'out_for_delivery' || $order['order_status'] === 'delivered') ? 'completed' : '' ?>">
                <span class="circle"></span>
                <p>Packed</p>
            </div>

            <div class="step <?= ($order['order_status'] === 'out_for_delivery' || $order['order_status'] === 'delivered') ? 'completed' : '' ?>">
                <span class="circle"></span>
                <p>Out for Delivery</p>
            </div>

            <div class="step <?= ($order['order_status'] === 'delivered') ? 'completed' : '' ?>">
                <span class="circle"></span>
                <p>Delivered</p>
            </div>

        </div>


        <!-- ORDER ITEMS (Future Ready) -->
        <div class="items-section">
            <h2>🛍 Items Ordered</h2>

            <?php if (!empty($order_items)): ?>
    <div class="items-list">

        <?php foreach ($order_items as $item): ?>

        <div class="item-card">
            <div class="item-image">
                <img src="../<?= htmlspecialchars($item['product_image']) ?>" alt="">
            </div>

            <div class="item-info">
                <h4><?= htmlspecialchars($item['product_name']) ?></h4>

                <p>Price: RM <?= number_format($item['unit_price'], 2) ?></p>

                <p>Quantity: <?= $item['quantity'] ?></p>

                <p><strong>Subtotal: RM <?= number_format($item['subtotal'], 2) ?></strong></p>
            </div>
        </div>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <p style="color:#888;">No items found for this order.</p>

<?php endif; ?>

        </div>


        <!-- BACK BUTTON -->
        <a href="orders.php" class="btn btn-primary" style="margin-top:20px;">
            ← Back to Orders
        </a>

    </div>
</section>

<style>
.order-details-section { padding: 40px 0; }
.order-status-box { margin-bottom: 20px; display:flex; gap:10px; }
.order-summary, .order-notes { background:#fff; padding:20px; border-radius:8px; margin-top:20px; }
.address-section { display:flex; gap:20px; margin-top:20px; }
.address-card { flex:1; background:#fff; padding:20px; border-radius:8px; }
.items-section { margin-top:30px; background:#fff; padding:20px; border-radius:8px; }

.status-badge, .payment-badge {
    padding:6px 14px;
    border-radius:20px;
    font-weight:bold;
    text-transform:capitalize;
    font-size:13px;
}

.status-pending { background:#fff3cd; color:#856404; }
.status-preparing { background:#cce5ff; color:#004085; }
.status-packed { background:#d1ecf1; color:#0c5460; }
.status-out_for_delivery { background:#e2e3ff; color:#383d7a; }
.status-delivered { background:#d4edda; color:#155724; }
.status-cancelled { background:#f8d7da; color:#721c24; }

.payment-pending { background:#fff3cd; color:#856404; }
.payment-paid { background:#d4edda; color:#155724; }
.payment-failed { background:#f8d7da; color:#721c24; }
.payment-refunded { background:#cce5ff; color:#004085; }

/* Timeline */
.tracking-timeline {
    display:flex;
    justify-content:space-between;
    margin:30px 0;
}

.step {
    text-align:center;
    flex:1;
    position:relative;
}

.step .circle {
    width:20px;
    height:20px;
    display:inline-block;
    border-radius:50%;
    background:#ccc;
    margin-bottom:10px;
}

.step.completed .circle {
    background:#28a745;
}

.step::after {
    content:"";
    position:absolute;
    top:10px;
    right:-50%;
    width:100%;
    height:4px;
    background:#ccc;
    z-index:-1;
}

.step.completed::after {
    background:#28a745;
}

.step:last-child::after { display:none; }
.items-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.item-card {
    display: flex;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    gap: 20px;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.item-image img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.item-info h4 {
    margin: 0 0 5px 0;
}

.item-info p {
    margin: 3px 0;
}

</style>

<?php include "../includes/footer.php"; ?>
