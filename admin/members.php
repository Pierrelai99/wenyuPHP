<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}


require_once '../includes/db.php';

// --- Handle Delete Customer ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_code'])) {
    $delete_code = $_POST['delete_code'];

    try {
        $pdo->prepare("DELETE FROM seafood_user_profiles WHERE user_code = ?")->execute([$delete_code]);
        $pdo->prepare("DELETE FROM seafood_users WHERE user_code = ?")->execute([$delete_code]);


        $_SESSION['success'] = "Customer account deleted successfully!";
    } catch (Throwable $e) {
        $_SESSION['error'] = "Error deleting customer: " . $e->getMessage();
    }

    header("Location: customers.php");
    exit;
}

// --- Handle Role Update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_code = $_POST['user_code'];
    $new_role = $_POST['new_role'];

    try {
        $stmt = $pdo->prepare("UPDATE seafood_users SET user_role = ? WHERE user_code = ?");
        $stmt->execute([$new_role, $user_code]);
        $_SESSION['success'] = "Customer role updated successfully!";
    } catch (Throwable $e) {
        $_SESSION['error'] = "Error updating role: " . $e->getMessage();
    }

    header("Location: customers.php");
    exit;
}

// --- Handle Toggle Promotions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_promotions'])) {
    $user_code = $_POST['user_code'];
    $current_value = $_POST['current_promotions'];
    $new_value = $current_value ? 0 : 1;

    try {
        $stmt = $pdo->prepare("UPDATE seafood_user_profiles SET receive_promotions = ? WHERE user_code = ?");
        $stmt->execute([$new_value, $user_code]);
        $_SESSION['success'] = "Promotions preference updated!";
    } catch (Throwable $e) {
        $_SESSION['error'] = "Error updating promotions: " . $e->getMessage();
    }

    header("Location: customers.php");
    exit;
}

// --- Handle Search & Sort ---
$search = $_GET['search'] ?? '';
$role_filter = $_GET['role'] ?? 'all';
$sort = $_GET['sort'] ?? 'newest';

$where = "WHERE u.user_role != 'admin'"; // Exclude admin accounts
$params = [];

if ($search) {
    $where .= " AND (u.user_name LIKE ? OR u.email LIKE ? OR u.user_code LIKE ? OR p.full_name LIKE ?)";
    $params = ["%$search%", "%$search%", "%$search%", "%$search%"];
}

if ($role_filter !== 'all') {
    $where .= " AND u.user_role = ?";
    $params[] = $role_filter;
}

switch ($sort) {
    case 'oldest':
        $orderBy = "u.created_on ASC";
        break;
    case 'name_asc':
        $orderBy = "u.user_name ASC";
        break;
    case 'name_desc':
        $orderBy = "u.user_name DESC";
        break;
    case 'email_asc':
        $orderBy = "u.email ASC";
        break;
    case 'email_desc':
        $orderBy = "u.email DESC";
        break;
    default: // newest
        $orderBy = "u.created_on DESC";
        break;
}

$sql = "SELECT 
            u.user_code,
            u.user_name,
            u.email,
            u.user_role,
            u.created_on,
            u.updated_on,
            p.profile_id,
            p.full_name,
            p.phone_no,
            p.dob,
            p.address,
            p.receive_updates,
            p.receive_promotions
        FROM seafood_users u
        LEFT JOIN seafood_user_profiles p ON u.user_code = p.user_code
        $where
        ORDER BY $orderBy";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stats_sql = "SELECT 
                COUNT(*) as total_customers,
                SUM(CASE WHEN user_role = 'customer' THEN 1 ELSE 0 END) as customers,
                SUM(CASE WHEN user_role = 'admin' THEN 1 ELSE 0 END) as admins
              FROM seafood_users";
$stats = $pdo->query($stats_sql)->fetch(PDO::FETCH_ASSOC);

$page_title = "Manage Customers";
include '../includes/header.php';
?>

<section class="admin-section customers-management">
    <div class="container">
        <h2>Manage Customer Accounts</h2>

        <!-- Statistics -->
        <div class="stats-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:30px;">
            <div class="stat-card" style="background:#f8f9fa; padding:20px; border-radius:8px;">
                <h3 style="margin:0; color:#666; font-size:14px;">Total Customers</h3>
                <p style="margin:10px 0 0; font-size:32px; font-weight:bold; color:#333;">
                    <?= $stats['customers'] ?>
                </p>
            </div>
            <div class="stat-card" style="background:#d4edda; padding:20px; border-radius:8px;">
                <h3 style="margin:0; color:#155724; font-size:14px;">Active Accounts</h3>
                <p style="margin:10px 0 0; font-size:32px; font-weight:bold; color:#155724;">
                    <?= count($customers) ?>
                </p>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" style="background:#d4edda; color:#155724; padding:15px; border-radius:5px; margin-bottom:20px;">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error" style="background:#f8d7da; color:#721c24; padding:15px; border-radius:5px; margin-bottom:20px;">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Filters & Search -->
        <div class="filters" style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap;">
            <form method="get" action="members.php" style="flex:1; display:flex; gap:10px; min-width:300px;">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search by name, email, or code..." 
                       style="flex:1; padding:10px; border:1px solid #ddd; border-radius:5px;">
                
                <select name="role" style="padding:10px; border:1px solid #ddd; border-radius:5px;">
                    <option value="all" <?= $role_filter=='all'?'selected':'' ?>>All Roles</option>
                    <option value="customer" <?= $role_filter=='customer'?'selected':'' ?>>Customers</option>
                    <option value="admin" <?= $role_filter=='admin'?'selected':'' ?>>Admins</option>
                </select>
                
                <select name="sort" style="padding:10px; border:1px solid #ddd; border-radius:5px;">
                    <option value="newest" <?= $sort=='newest'?'selected':'' ?>>Newest First</option>
                    <option value="oldest" <?= $sort=='oldest'?'selected':'' ?>>Oldest First</option>
                    <option value="name_asc" <?= $sort=='name_asc'?'selected':'' ?>>Name A–Z</option>
                    <option value="name_desc" <?= $sort=='name_desc'?'selected':'' ?>>Name Z–A</option>
                    <option value="email_asc" <?= $sort=='email_asc'?'selected':'' ?>>Email A–Z</option>
                    <option value="email_desc" <?= $sort=='email_desc'?'selected':'' ?>>Email Z–A</option>
                </select>
                
                <button type="submit" class="btn btn-primary" style="padding:10px 20px; background:#007bff; color:#fff; border:none; border-radius:5px; cursor:pointer;">
                    Search
                </button>
            </form>
        </div>

        <!-- Customers Table -->
        <div style="overflow-x: auto; background:#fff; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
            <table class="table" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8f9fa; border-bottom:2px solid #dee2e6;">
                        <th style="padding:15px; text-align:left;">User Code</th>
                        <th style="padding:15px; text-align:left;">Username</th>
                        <th style="padding:15px; text-align:left;">Email</th>
                        <th style="padding:15px; text-align:left;">Full Name</th>
                        <th style="padding:15px; text-align:left;">Phone</th>
                        <th style="padding:15px; text-align:left;">Date of Birth</th>
                        <th style="padding:15px; text-align:left;">Role</th>
                        <th style="padding:15px; text-align:center;">Promotions</th>
                        <th style="padding:15px; text-align:center;">Updates</th>
                        <th style="padding:15px; text-align:left;">Joined</th>
                        <th style="padding:15px; text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr style="border-bottom:1px solid #dee2e6;">
                            <td style="padding:15px;">
                                <code style="background:#f8f9fa; padding:4px 8px; border-radius:4px; font-size:12px;">
                                    <?= htmlspecialchars($customer['user_code']) ?>
                                </code>
                            </td>
                            <td style="padding:15px;">
                                <strong><?= htmlspecialchars($customer['user_name']) ?></strong>
                            </td>
                            <td style="padding:15px;">
                                <a href="mailto:<?= htmlspecialchars($customer['email']) ?>" style="color:#007bff; text-decoration:none;">
                                    <?= htmlspecialchars($customer['email']) ?>
                                </a>
                            </td>
                            <td style="padding:15px;">
                                <?= htmlspecialchars($customer['full_name'] ?? '-') ?>
                            </td>
                            <td style="padding:15px;">
                                <?= htmlspecialchars($customer['phone_no'] ?? '-') ?>
                            </td>
                            <td style="padding:15px;">
                                <?= $customer['dob'] ? date('d M Y', strtotime($customer['dob'])) : '-' ?>
                            </td>
                            <td style="padding:15px;">
                                <span class="role-badge role-<?= $customer['user_role'] ?>" 
                                      style="padding:4px 12px; border-radius:12px; font-size:12px; font-weight:bold; text-transform:uppercase;">
                                    <?= ucfirst($customer['user_role']) ?>
                                </span>
                            </td>
                            <td style="padding:15px; text-align:center;">
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="user_code" value="<?= $customer['user_code'] ?>">
                                    <input type="hidden" name="current_promotions" value="<?= $customer['receive_promotions'] ?>">
                                    <button type="submit" name="toggle_promotions" 
                                            style="border:none; background:none; cursor:pointer; font-size:20px;"
                                            title="<?= $customer['receive_promotions'] ? 'Enabled' : 'Disabled' ?>">
                                        <?= $customer['receive_promotions'] ? '✅' : '❌' ?>
                                    </button>
                                </form>
                            </td>
                            <td style="padding:15px; text-align:center;">
                                <?= $customer['receive_updates'] ? '✅' : '❌' ?>
                            </td>
                            <td style="padding:15px;">
                                <small style="color:#666;">
                                    <?= date('d M Y', strtotime($customer['created_on'])) ?>
                                </small>
                            </td>
                            <td style="padding:15px; text-align:center; white-space:nowrap;">
                                <a href="customer_view.php?code=<?= urlencode($customer['user_code']) ?>" 
                                   class="btn btn-small" 
                                   style="display:inline-block; padding:6px 12px; background:#17a2b8; color:#fff; text-decoration:none; border-radius:4px; font-size:12px; margin:2px;">
                                    View
                                </a>
                                <a href="customer_edit.php?code=<?= urlencode($customer['user_code']) ?>" 
                                   class="btn btn-small" 
                                   style="display:inline-block; padding:6px 12px; background:#ffc107; color:#000; text-decoration:none; border-radius:4px; font-size:12px; margin:2px;">
                                    Edit
                                </a>
                                <form method="post" style="display:inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this customer account? This action cannot be undone.');">
                                    <input type="hidden" name="delete_code" value="<?= $customer['user_code'] ?>">
                                    <button type="submit" 
                                            class="btn btn-small btn-delete" 
                                            style="padding:6px 12px; background:#dc3545; color:#fff; border:none; border-radius:4px; font-size:12px; cursor:pointer; margin:2px;">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="11" style="text-align:center; padding:40px; color:#666;">
                                <p style="font-size:18px; margin:0;">No customers found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Export Button -->
        <div style="margin-top:20px;">
            <a href="export_customers.php" class="btn btn-success" 
               style="display:inline-block; padding:10px 20px; background:#28a745; color:#fff; text-decoration:none; border-radius:5px;">
                📊 Export Customers to CSV
            </a>
        </div>
    </div>
</section>

<style>
.role-badge.role-customer {
    background-color: #d4edda;
    color: #155724;
}
.role-badge.role-admin {
    background-color: #cce5ff;
    color: #004085;
}
.btn:hover {
    opacity: 0.8;
}
.table tr:hover {
    background-color: #f8f9fa;
}
</style>

<?php include '../includes/footer.php'; ?>