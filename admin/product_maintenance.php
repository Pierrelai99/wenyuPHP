<?php
session_start();
require_once "../includes/db.php";

// Only admin can access
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

/* ---------------------------------------------------------
   1. LOAD CATEGORIES (for dropdown)
---------------------------------------------------------- */
$cat_stmt = $pdo->query("SELECT category_id, category_name FROM seafood_categories WHERE status='active'");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------------------------------------------------------
   2. DELETE PRODUCT
---------------------------------------------------------- */
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    $del = $pdo->prepare("DELETE FROM seafood_products WHERE product_id = ?");
    $del->execute([$delete_id]);

    $_SESSION['success'] = "Product deleted successfully.";
    header("Location: product_maintenance.php");
    exit();
}

/* ---------------------------------------------------------
   3. ADD PRODUCT
---------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {

    $name = trim($_POST['product_name']);
    $desc = trim($_POST['product_desc']);
    $price = $_POST['price_per_kg'];
    $promo = $_POST['promo_price'] ?: null;
    $stock = $_POST['stock_kg'];
    $category = $_POST['category_id'];
    $freshness = $_POST['freshness_level'];
    $origin = trim($_POST['origin_country']);
    $weight = $_POST['weight_per_unit'] ?: null;
    $harvest = $_POST['harvest_date'];
    $storage = trim($_POST['storage_temp']);
    $status = $_POST['status'];
    $featured = isset($_POST['featured']) ? 1 : 0;

    /* ---------------------------
       UPLOAD IMAGE
    -----------------------------*/
    $image_path = null;

    if (!empty($_FILES['product_image']['name'])) {
        $file_name = time() . "_" . basename($_FILES['product_image']['name']);
        $target = "../assets/products/" . $file_name;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
            $image_path = "assets/products/" . $file_name;
        }
    }

    $insert = $pdo->prepare("
        INSERT INTO seafood_products 
        (product_name, product_desc, price_per_kg, promo_price, stock_kg, category_id, 
         product_image, freshness_level, origin_country, weight_per_unit, harvest_date, 
         storage_temp, status, featured)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->execute([
        $name, $desc, $price, $promo, $stock, $category,
        $image_path, $freshness, $origin, $weight, $harvest,
        $storage, $status, $featured
    ]);

    $_SESSION['success'] = "Product added successfully!";
    header("Location: product_maintenance.php");
    exit();
}

/* ---------------------------------------------------------
   4. LOAD ALL PRODUCTS
---------------------------------------------------------- */
$stmt = $pdo->query("
    SELECT p.*, c.category_name
    FROM seafood_products p
    LEFT JOIN seafood_categories c ON p.category_id = c.category_id
    ORDER BY p.created_on DESC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Product Maintenance";
include "../includes/header.php";
?>

<section class="admin-section">
    <div class="container">

        <h1>🛠 Product Maintenance</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- ADD PRODUCT FORM -->
        <div class="admin-box">
            <h2>➕ Add New Product</h2>

            <form method="POST" enctype="multipart/form-data" class="admin-form">
                
                <label>Product Name</label>
                <input type="text" name="product_name" required>

                <label>Description</label>
                <textarea name="product_desc" rows="3"></textarea>

                <label>Price per KG</label>
                <input type="number" step="0.01" name="price_per_kg" required>

                <label>Promo Price (optional)</label>
                <input type="number" step="0.01" name="promo_price">

                <label>Stock (KG)</label>
                <input type="number" step="0.1" name="stock_kg" required>

                <label>Category</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Freshness Level</label>
                <select name="freshness_level">
                    <option value="fresh">Fresh</option>
                    <option value="frozen">Frozen</option>
                    <option value="live">Live</option>
                    <option value="processed">Processed</option>
                </select>

                <label>Origin Country</label>
                <input type="text" name="origin_country">

                <label>Weight per unit (g) (optional)</label>
                <input type="number" step="0.1" name="weight_per_unit">

                <label>Harvest Date</label>
                <input type="date" name="harvest_date">

                <label>Storage Temperature</label>
                <input type="text" name="storage_temp">

                <label>Status</label>
                <select name="status">
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                    <option value="sold_out">Sold Out</option>
                </select>

                <label>
                    <input type="checkbox" name="featured"> Featured Product
                </label>

                <label>Product Image</label>
                <input type="file" name="product_image" accept="image/*">

                <button class="btn btn-primary" name="add_product">Add Product</button>
            </form>
        </div>

        <!-- PRODUCT TABLE -->
        <div class="admin-box">
            <h2>📦 All Products</h2>

            <table class="admin-table">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price (RM)</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><img src="../<?= $p['product_image'] ?>" width="60"></td>
                        <td><?= htmlspecialchars($p['product_name']) ?></td>
                        <td><?= htmlspecialchars($p['category_name']) ?></td>
                        <td><?= number_format($p['price_per_kg'], 2) ?></td>
                        <td><?= $p['stock_kg'] ?> kg</td>
                        <td><?= ucfirst($p['status']) ?></td>
                        <td>
                            <a href="product_edit.php?id=<?= $p['product_id'] ?>" class="btn btn-warning">Edit</a>
                            <a href="product_maintenance.php?delete=<?= $p['product_id'] ?>" 
                               onclick="return confirm('Delete this product?')" 
                               class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

        </div>

    </div>
</section>

<style>
.admin-section { padding: 30px 0; }
.admin-box { background:#fff; padding:20px; border-radius:8px; margin-top:20px; }
.admin-form input, select, textarea { width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc; }
.admin-table { width:100%; border-collapse:collapse; margin-top:20px; }
.admin-table th, td { padding:10px; border-bottom:1px solid #ddd; text-align:left; }
.btn { padding:6px 12px; border-radius:5px; text-decoration:none; }
.btn-warning { background:#f0ad4e; color:#fff; }
.btn-danger { background:#d9534f; color:#fff; }
.btn-primary { background:#0275d8; color:#fff; }
</style>

<?php include "../includes/footer.php"; ?>
