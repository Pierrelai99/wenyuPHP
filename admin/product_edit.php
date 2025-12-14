<?php
session_start();
require_once "../includes/db.php";

// Only admin can access
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Validate product ID
if (!isset($_GET['id'])) {
    header("Location: product_maintenance.php");
    exit();
}

$product_id = intval($_GET['id']);

/* ---------------------------------------------------------
   1. LOAD PRODUCT DATA
---------------------------------------------------------- */
$stmt = $pdo->prepare("
    SELECT * FROM seafood_products WHERE product_id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: product_maintenance.php");
    exit();
}

/* ---------------------------------------------------------
   2. LOAD CATEGORIES
---------------------------------------------------------- */
$cat_stmt = $pdo->query("SELECT category_id, category_name FROM seafood_categories WHERE status='active'");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------------------------------------------------------
   3. HANDLE UPDATE SUBMISSION
---------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['product_name']);
    $desc = trim($_POST['product_desc']);
    $price = $_POST['price_per_kg'];
    $promo = $_POST['promo_price'] ?: null;
    $stock = $_POST['stock_kg'];
    $category = $_POST['category_id'];
    $freshness = $_POST['freshness_level'];
    $origin = trim($_POST['origin_country']);
    $weight = $_POST['weight_per_unit'] ?: null;
    $harvest = $_POST['harvest_date'] ?: null;
    $storage = trim($_POST['storage_temp']);
    $status = $_POST['status'];
    $featured = isset($_POST['featured']) ? 1 : 0;

    /* ---------------------------
       IMAGE UPLOAD (optional)
    -----------------------------*/
    $image_path = $product['product_image']; // default old image

    if (!empty($_FILES['product_image']['name'])) {

        $file_name = time() . "_" . basename($_FILES['product_image']['name']);
        $target = "../assets/products/" . $file_name;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
            $image_path = "assets/products/" . $file_name;
        }
    }

    /* ---------------------------
       UPDATE PRODUCT
    -----------------------------*/
    $update = $pdo->prepare("
        UPDATE seafood_products
        SET product_name = ?, product_desc = ?, price_per_kg = ?, promo_price = ?, 
            stock_kg = ?, category_id = ?, product_image = ?, freshness_level = ?,
            origin_country = ?, weight_per_unit = ?, harvest_date = ?, storage_temp = ?,
            status = ?, featured = ?
        WHERE product_id = ?
    ");

    $update->execute([
        $name, $desc, $price, $promo, $stock, $category,
        $image_path, $freshness, $origin, $weight, $harvest,
        $storage, $status, $featured,
        $product_id
    ]);

    $_SESSION['success'] = "Product updated successfully!";

    header("Location: product_maintenance.php");
    exit();
}

// Page UI
$page_title = "Edit Product";
include "../includes/admin_header.php";
?>

<section class="admin-section">
    <div class="container">

        <h1>✏️ Edit Product</h1>

        <a href="product_maintenance.php" class="btn btn-secondary" style="margin-bottom:20px;">
            ← Back to Product Maintenance
        </a>

        <div class="admin-box">

            <form method="POST" enctype="multipart/form-data" class="admin-form">

                <label>Product Name</label>
                <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

                <label>Description</label>
                <textarea name="product_desc" rows="3"><?= htmlspecialchars($product['product_desc']) ?></textarea>

                <label>Price per KG</label>
                <input type="number" step="0.01" name="price_per_kg" value="<?= $product['price_per_kg'] ?>" required>

                <label>Promo Price</label>
                <input type="number" step="0.01" name="promo_price" value="<?= $product['promo_price'] ?>">

                <label>Stock (KG)</label>
                <input type="number" step="0.1" name="stock_kg" value="<?= $product['stock_kg'] ?>" required>

                <label>Category</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>" 
                            <?= ($product['category_id'] == $c['category_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Freshness Level</label>
                <select name="freshness_level">
                    <?php foreach (['fresh','frozen','live','processed'] as $fl): ?>
                        <option value="<?= $fl ?>" <?= ($product['freshness_level'] === $fl) ? 'selected' : '' ?>>
                            <?= ucfirst($fl) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Origin Country</label>
                <input type="text" name="origin_country" value="<?= htmlspecialchars($product['origin_country']) ?>">

                <label>Weight per unit (g)</label>
                <input type="number" step="0.1" name="weight_per_unit" value="<?= $product['weight_per_unit'] ?>">

                <label>Harvest Date</label>
                <input type="date" name="harvest_date" value="<?= $product['harvest_date'] ?>">

                <label>Storage Temperature</label>
                <input type="text" name="storage_temp" value="<?= htmlspecialchars($product['storage_temp']) ?>">

                <label>Status</label>
                <select name="status">
                    <?php foreach (['available','unavailable','sold_out'] as $st): ?>
                        <option value="<?= $st ?>" <?= ($product['status'] === $st) ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('_',' ', $st)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>
                    <input type="checkbox" name="featured" <?= $product['featured'] ? 'checked' : '' ?>>
                    Featured Product
                </label>

                <label>Current Image</label><br>
                <img src="../<?= $product['product_image'] ?>" width="120" style="border-radius:8px; margin-bottom:10px;"><br><br>

                <label>Replace Image (optional)</label>
                <input type="file" name="product_image" accept="image/*">

                <button class="btn btn-primary" style="margin-top:20px;">Update Product</button>

            </form>

        </div>

    </div>
</section>

<style>
.admin-section { padding: 30px 0; }
.admin-box { background:#fff; padding:20px; border-radius:8px; }
.admin-form input, select, textarea {
    width:100%; padding:10px; margin-bottom:12px;
    border-radius:5px; border:1px solid #ccc;
}
.btn-secondary { background:#6c757d; color:#fff; padding:8px 15px; border-radius:5px; }
.btn-primary { background:#0275d8; color:#fff; padding:10px 18px; border-radius:5px; }
</style>

<?php include "../includes/admin_footer.php"; ?>
