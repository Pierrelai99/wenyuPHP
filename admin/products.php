<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

require_once '../includes/db.php';

// --- Handle Delete ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    try {
        // 1. Fetch the product to get the main image path
        $stmt = $pdo->prepare("SELECT product_image FROM products WHERE product_id = ?");
        $stmt->execute([$delete_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Delete main image file (skip placeholder)
        if (!empty($product['product_image']) && strpos($product['product_image'], 'no-image.png') === false) {
            $mainImagePath = __DIR__ . '/../' . $product['product_image'];
            if (file_exists($mainImagePath)) {
                unlink($mainImagePath);
            }
        }

        // 3. Delete gallery images (files + DB records) - if you have a product_images table
        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
        $stmt->execute([$delete_id]);
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($images as $img) {
            $filePath = __DIR__ . '/../' . $img;
            if ($img && file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $pdo->prepare("DELETE FROM product_images WHERE product_id = ?")->execute([$delete_id]);

        // 4. Delete product record
        $pdo->prepare("DELETE FROM products WHERE product_id = ?")->execute([$delete_id]);

        $_SESSION['success'] = "Seafood product deleted successfully!";
    } catch (Throwable $e) {
        $_SESSION['error'] = "Error deleting seafood product: " . $e->getMessage();
    }

    header("Location: products.php");
    exit;
}

// --- Handle Search & Sort ---
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$where = '';
$params = [];
if ($search) {
    $where = "WHERE p.product_name LIKE ? OR p.product_desc LIKE ? OR c.name LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
}

switch ($sort) {
    case 'oldest':
        $orderBy = "p.created_on ASC";
        break;
    case 'name_asc':
        $orderBy = "p.product_name ASC";
        break;
    case 'name_desc':
        $orderBy = "p.product_name DESC";
        break;
    case 'price_asc':
        $orderBy = "p.price_per_kg ASC";
        break;
    case 'price_desc':
        $orderBy = "p.price_per_kg DESC";
        break;
    case 'stock_asc':
        $orderBy = "p.stock_kg ASC";
        break;
    case 'stock_desc':
        $orderBy = "p.stock_kg DESC";
        break;
    default: // newest
        $orderBy = "p.created_on DESC";
        break;
}

$sql = "SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        $where
        ORDER BY $orderBy";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Manage Seafood Products";
include '../includes/header.php';
?>

<section class="admin-section products-management">
    <div class="container">
        <h2>Manage Seafood Products</h2>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="filters" style="margin-bottom:20px; display:flex; gap:10px;">
            <form method="get" action="products.php" style="flex:1; display:flex; gap:10px;">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search seafood products..." style="flex:1; padding:5px;">
                <select name="sort" onchange="this.form.submit()">
                    <option value="newest" <?= $sort=='newest'?'selected':'' ?>>Newest First</option>
                    <option value="oldest" <?= $sort=='oldest'?'selected':'' ?>>Oldest First</option>
                    <option value="name_asc" <?= $sort=='name_asc'?'selected':'' ?>>Name A–Z</option>
                    <option value="name_desc" <?= $sort=='name_desc'?'selected':'' ?>>Name Z–A</option>
                    <option value="price_asc" <?= $sort=='price_asc'?'selected':'' ?>>Price Low → High</option>
                    <option value="price_desc" <?= $sort=='price_desc'?'selected':'' ?>>Price High → Low</option>
                    <option value="stock_asc" <?= $sort=='stock_asc'?'selected':'' ?>>Stock Low → High</option>
                    <option value="stock_desc" <?= $sort=='stock_desc'?'selected':'' ?>>Stock High → Low</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <a href="product_add.php" class="btn btn-success">+ Add New Seafood</a>
        </div>

        <!-- Products Table -->
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>ID</th>
                        <th>Seafood Name</th>
                        <th>Category</th>
                        <th>Price/kg</th>
                        <th>Promo Price</th>
                        <th>Stock (kg)</th>
                        <th>Freshness</th>
                        <th>Origin</th>
                        <th>Weight/Unit</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <?php if ($p['product_image']): ?>
                                    <img src="../<?= htmlspecialchars($p['product_image']) ?>" 
                                         alt="<?= htmlspecialchars($p['product_name']) ?>" 
                                         width="80">
                                <?php else: ?>
                                    <span>No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($p['product_id']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($p['product_name']) ?></strong>
                                <?php if (!empty($p['product_desc'])): ?>
                                    <br><small style="color:#666;"><?= htmlspecialchars(substr($p['product_desc'], 0, 50)) ?>...</small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($p['category_name'] ?? 'Uncategorized') ?></td>
                            <td>RM <?= number_format($p['price_per_kg'], 2) ?></td>
                            <td>
                                <?php if (!empty($p['promo_price'])): ?>
                                    RM <?= number_format($p['promo_price'], 2) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($p['stock_kg'], 2) ?> kg</td>
                            <td><?= ucfirst(htmlspecialchars($p['freshness_level'])) ?></td>
                            <td><?= htmlspecialchars($p['origin_country'] ?? '-') ?></td>
                            <td>
                                <?php if (!empty($p['weight_per_unit'])): ?>
                                    <?= number_format($p['weight_per_unit'], 2) ?> kg
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $p['status'] ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?= $p['featured'] ? '⭐ Yes' : 'No' ?>
                            </td>
                            <td style="white-space: nowrap;">
                                <a href="product_edit.php?id=<?= urlencode($p['product_id']) ?>" 
                                   class="btn btn-small">Edit</a>
                                <form method="post" action="products.php" style="display:inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this seafood product?');">
                                    <input type="hidden" name="delete_id" value="<?= $p['product_id'] ?>">
                                    <button type="submit" class="btn btn-small btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="13" style="text-align:center; padding:20px;">
                                No seafood products found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<style>
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}
.status-available {
    background-color: #d4edda;
    color: #155724;
}
.status-unavailable {
    background-color: #f8d7da;
    color: #721c24;
}
.status-sold_out {
    background-color: #fff3cd;
    color: #856404;
}
</style>

<?php include '../includes/footer.php'; ?>