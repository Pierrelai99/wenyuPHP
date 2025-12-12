<?php
session_start();

// Allow only logged-in customers
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../public/login.php');
    exit();
}

require_once '../includes/db.php';

// Load orders for this user
$user_code = $_SESSION['user_code'];

$stmt = $pdo->prepare("
    SELECT order_id, order_no, total_price, order_status, payment_status, created_on
    FROM seafood_orders
    WHERE user_code = ?
    ORDER BY created_on DESC
");
$stmt->execute([$user_code]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page variables
$page_title = "My Orders";
$page_description = "View your seafood purchase history";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'orders.php', 'title' => 'My Orders']
];

// Header
include '../includes/header.php';
?>

<section class="orders-section">
    <div class="container">
        <div class="orders-header">
            <h1>🛒 My Orders</h1>
            <p>Track your seafood purchases and deliveries</p>
        </div>

        <?php if (empty($orders)): ?>
            <div class="empty-orders">
                <i class="fas fa-box-open"></i>
                <h2>No Orders Yet</h2>
                <p>You haven't placed any orders. Start shopping now!</p>
                <a href="../public/products.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>

        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($o['order_no']) ?></strong></td>

                        <td><?= date("d M Y, h:i A", strtotime($o['created_on'])) ?></td>

                        <td>RM <?= number_format($o['total_price'], 2) ?></td>

                        <td>
                            <span class="status-badge status-<?= $o['order_status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $o['order_status'])) ?>
                            </span>
                        </td>

                        <td>
                            <span class="payment-badge payment-<?= $o['payment_status'] ?>">
                                <?= ucfirst($o['payment_status']) ?>
                            </span>
                        </td>

                        <td>
                            <a href="order_details.php?order=<?= $o['order_id'] ?>" 
                               class="btn btn-small btn-primary">
                                View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php endif; ?>
    </div>
</section>

<style>
.orders-section { padding: 40px 0; }

.orders-header h1 {
    font-size: 32px; 
    margin-bottom: 10px;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

.orders-table th, 
.orders-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.orders-table th {
    background: #f8f9fa;
    font-weight: bold;
}

.status-badge, .payment-badge {
    padding: 5px 12px;
    border-radius: 25px;
    font-size: 12px;
    font-weight: bold;
    text-transform: capitalize;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-preparing { background: #cce5ff; color: #004085; }
.status-packed { background: #d1ecf1; color: #0c5460; }
.status-out_for_delivery { background: #e2e3ff; color: #383d7a; }
.status-delivered { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.payment-pending { background: #fff3cd; color: #856404; }
.payment-paid { background: #d4edda; color: #155724; }
.payment-failed { background: #f8d7da; color: #721c24; }
.payment-refunded { background: #cce5ff; color: #004085; }

.btn-small {
    padding: 6px 12px;
    font-size: 14px;
    text-decoration: none;
    border-radius: 6px;
}

.empty-orders {
    text-align: center;
    padding: 50px 0;
}

.empty-orders i {
    font-size: 60px;
    color: #aaa;
}

.empty-orders h2 {
    margin-top: 10px;
    font-size: 28px;
}
</style>

<?php include '../includes/footer.php'; ?>
