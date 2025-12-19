<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

require_once '../includes/db.php';

// --- Handle Order Status Update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    try {
        $stmt = $pdo->prepare("UPDATE seafood_orders SET order_status = ?, updated_on = NOW() WHERE order_id = ?");
        $stmt->execute([$new_status, $order_id]);
        $_SESSION['success'] = "Order status updated successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating order status: " . $e->getMessage();
    }

    header("Location: orders.php");
    exit;
}

// --- Handle Order Delete ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    try {
        // Delete order items first (foreign key constraint)
        $pdo->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$order_id]);
        // Delete order
        $pdo->prepare("DELETE FROM seafood_orders WHERE order_id = ?")->execute([$order_id]);
        $_SESSION['success'] = "Order deleted successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting order: " . $e->getMessage();
    }

    header("Location: orders.php");
    exit;
}

// --- Handle Search & Filter ---
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$payment_filter = $_GET['payment'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$where = "WHERE 1=1";
$params = [];

if ($search) {
    $where .= " AND (o.order_id LIKE ? OR o.order_no LIKE ? OR u.user_name LIKE ? OR u.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status_filter) {
    $where .= " AND o.order_status = ?";
    $params[] = $status_filter;
}

if ($payment_filter) {
    $where .= " AND o.payment_status = ?";
    $params[] = $payment_filter;
}

switch ($sort) {
    case 'oldest':
        $orderBy = "o.created_on ASC";
        break;
    case 'amount_asc':
        $orderBy = "o.total_price ASC";
        break;
    case 'amount_desc':
        $orderBy = "o.total_price DESC";
        break;
    default: // newest
        $orderBy = "o.created_on DESC";
        break;
}

$sql = "SELECT o.*, u.user_name, u.email 
        FROM seafood_orders o
        LEFT JOIN seafood_users u ON o.user_code = u.user_code
        $where
        ORDER BY $orderBy";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
$page_title = "Manage Orders";
include '../includes/header.php';
?>

<section class="admin-section orders-management">
    <div class="container">
        <div class="section-header">
            <h2>Manage Seafood Orders</h2>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="filters" style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap;">
            <form method="get" action="orders.php" style="flex:1; display:flex; gap:10px; flex-wrap:wrap;">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search by Order No, Customer..." style="flex:1; min-width:200px; padding:8px;">
                
                <select name="status" style="padding:8px;">
                    <option value="">All Order Status</option>
                    <option value="pending" <?= $status_filter=='pending'?'selected':'' ?>>Pending</option>
                    <option value="preparing" <?= $status_filter=='preparing'?'selected':'' ?>>Preparing</option>
                    <option value="packed" <?= $status_filter=='packed'?'selected':'' ?>>Packed</option>
                    <option value="out_for_delivery" <?= $status_filter=='out_for_delivery'?'selected':'' ?>>Out for Delivery</option>
                    <option value="delivered" <?= $status_filter=='delivered'?'selected':'' ?>>Delivered</option>
                    <option value="cancelled" <?= $status_filter=='cancelled'?'selected':'' ?>>Cancelled</option>
                </select>

                <select name="payment" style="padding:8px;">
                    <option value="">All Payment Status</option>
                    <option value="pending" <?= $payment_filter=='pending'?'selected':'' ?>>Pending</option>
                    <option value="paid" <?= $payment_filter=='paid'?'selected':'' ?>>Paid</option>
                    <option value="failed" <?= $payment_filter=='failed'?'selected':'' ?>>Failed</option>
                    <option value="refunded" <?= $payment_filter=='refunded'?'selected':'' ?>>Refunded</option>
                </select>

                <select name="sort" style="padding:8px;">
                    <option value="newest" <?= $sort=='newest'?'selected':'' ?>>Newest First</option>
                    <option value="oldest" <?= $sort=='oldest'?'selected':'' ?>>Oldest First</option>
                    <option value="amount_desc" <?= $sort=='amount_desc'?'selected':'' ?>>Amount High → Low</option>
                    <option value="amount_asc" <?= $sort=='amount_asc'?'selected':'' ?>>Amount Low → High</option>
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <!-- Orders Table -->
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Delivery</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($order['order_no']) ?></strong></td>
                            <td>
                                <div><?= htmlspecialchars($order['user_name'] ?? 'Guest') ?></div>
                                <small style="color:#666;"><?= htmlspecialchars($order['email'] ?? '-') ?></small>
                            </td>
                            <td><?= date('d M Y, h:i A', strtotime($order['created_on'])) ?></td>
                            <td><strong>RM <?= number_format($order['total_price'], 2) ?></strong></td>
                            <td>
                                <span class="badge badge-<?= $order['payment_status'] ?>">
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                                <br>
                                <small><?= htmlspecialchars($order['payment_method'] ?? '-') ?></small>
                            </td>
                            <td>
                                <span class="badge badge-<?= $order['delivery_type'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $order['delivery_type'])) ?>
                                </span>
                                <?php if ($order['delivery_time_slot']): ?>
                                    <br><small><?= htmlspecialchars($order['delivery_time_slot']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="orders.php" style="display:inline;">
                                    <input type="hidden" name="update_status" value="1">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <select name="order_status" onchange="if(confirm('Update order status?')) this.form.submit();" 
                                            class="status-select status-<?= $order['order_status'] ?>">
                                        <option value="pending" <?= $order['order_status']=='pending'?'selected':'' ?>>Pending</option>
                                        <option value="preparing" <?= $order['order_status']=='preparing'?'selected':'' ?>>Preparing</option>
                                        <option value="packed" <?= $order['order_status']=='packed'?'selected':'' ?>>Packed</option>
                                        <option value="out_for_delivery" <?= $order['order_status']=='out_for_delivery'?'selected':'' ?>>Out for Delivery</option>
                                        <option value="delivered" <?= $order['order_status']=='delivered'?'selected':'' ?>>Delivered</option>
                                        <option value="cancelled" <?= $order['order_status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="../member/order_details.php?order=<?= $order['order_id'] ?>"
                                class="btn btn-small btn-primary">View</a>

                                <form method="post" action="orders.php" style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this order?');">
                                    <input type="hidden" name="delete_order" value="1">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <button type="submit" class="btn btn-small btn-delete">Delete</button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="8" style="text-align:center; padding:30px;">
                                <i class="fas fa-inbox" style="font-size:48px; color:#ccc; margin-bottom:10px;"></i>
                                <p>No orders found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Order Statistics -->
        <div class="order-stats" style="margin-top:30px; display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px;">
            <?php
            $stats = [
                'pending' => 0,
                'preparing' => 0,
                'packed' => 0,
                'out_for_delivery' => 0,
                'delivered' => 0,
                'cancelled' => 0
            ];
            $payment_stats = [
                'paid' => 0,
                'pending' => 0,
                'failed' => 0,
                'refunded' => 0
            ];
            $total_revenue = 0;
            
            foreach ($orders as $order) {
                if (isset($stats[$order['order_status']])) {
                    $stats[$order['order_status']]++;
                }
                if (isset($payment_stats[$order['payment_status']])) {
                    $payment_stats[$order['payment_status']]++;
                }
                if ($order['payment_status'] === 'paid') {
                    $total_revenue += $order['total_price'];
                }
            }
            ?>
            
            <div class="stat-card">
                <h4>Total Orders</h4>
                <p class="stat-number"><?= count($orders) ?></p>
            </div>
            <div class="stat-card">
                <h4>Pending</h4>
                <p class="stat-number"><?= $stats['pending'] ?></p>
            </div>
            <div class="stat-card">
                <h4>Preparing</h4>
                <p class="stat-number"><?= $stats['preparing'] ?></p>
            </div>
            <div class="stat-card">
                <h4>Delivered</h4>
                <p class="stat-number"><?= $stats['delivered'] ?></p>
            </div>
            <div class="stat-card">
                <h4>Paid Orders</h4>
                <p class="stat-number"><?= $payment_stats['paid'] ?></p>
            </div>
            <div class="stat-card">
                <h4>Total Revenue</h4>
                <p class="stat-number">RM <?= number_format($total_revenue, 2) ?></p>
            </div>
        </div>
    </div>
</section>

<style>
/* ---------- PAGE ---------- */
.orders-management {
    padding: 30px 0;
    color: #222;
    background: #f7f9fc;
}

.section-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 20px;
}

/* ---------- FILTER BAR ---------- */
.filters form {
    background: #ffffff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.filters input,
.filters select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    color: #111;
    background: #fff;
}

.filters input::placeholder {
    color: #6b7280;
}

.filters button {
    background: #0d6efd;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-weight: 600;
}

/* ---------- TABLE ---------- */
.table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
}

.table thead {
    background: #f1f5f9;
}

.table th {
    text-align: left;
    padding: 14px;
    font-size: 13px;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    padding: 14px;
    font-size: 14px;
    color: #111827;
    border-top: 1px solid #e5e7eb;
    vertical-align: middle;
}

.table tr:hover {
    background: #f9fafb;
}

/* ---------- BADGES ---------- */
.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

/* Payment */
.badge-paid { background:#dcfce7; color:#166534; }
.badge-pending { background:#fef3c7; color:#92400e; }
.badge-failed,
.badge-refunded { background:#fee2e2; color:#991b1b; }

/* Delivery */
.badge-pickup,
.badge-delivery,
.badge-tng {
    background:#e0f2fe;
    color:#075985;
}

/* ---------- STATUS SELECT ---------- */
.status-select {
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: #ffffff;
    font-size: 13px;
    color: #111;
    cursor: pointer;
}

/* ---------- BUTTONS ---------- */
.btn-small {
    padding: 6px 14px;
    font-size: 13px;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #0d6efd;
    color: #ffffff;
    border: none;
}

.btn-delete {
    background: #ef4444;
    color: #ffffff;
    border: none;
    margin-left: 6px;
}

.btn-primary:hover { background:#0b5ed7; }
.btn-delete:hover { background:#dc2626; }

/* ---------- STATS CARDS ---------- */
.order-stats {
    margin-top: 40px;
}

.stat-card {
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    text-align: center;
}

.stat-card h4 {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 6px;
    font-weight: 600;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

/* ---------- ALERTS ---------- */
.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
}
</style>


<?php include '../includes/footer.php'; ?>
