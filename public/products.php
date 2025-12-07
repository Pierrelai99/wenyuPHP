<?php
session_start();
require_once '../includes/db.php';

// DB connection
// $pdo = new PDO(
//     "mysql:host=localhost;dbname=dbassignment;charset=utf8mb4",
//     "root",
//     "Af@12345678"
// );
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Load categories (for sidebar)
$cat_stmt = $pdo->query("SELECT category_id, category_name FROM seafood_categories WHERE status='active'");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

// Load products
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $stmt = $pdo->prepare("SELECT * FROM seafood_products WHERE category_id = ? AND status = 'available'");
    $stmt->execute([$category_id]);
} else {
    $stmt = $pdo->query("SELECT * FROM seafood_products WHERE status = 'available'");
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page variables
$page_title = "Seafood Products";
$page_description = "Browse fresh seafood available today";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'products.php', 'title' => 'Seafood Products']
];

// Include header
include '../includes/header.php';
?>



<section class="page-header" style="background: linear-gradient(to right, #b5f3e1, #e3fff9); padding: 3em 0; border-radius: 0 0 50px 50px;">
    <div class="container">
        <h1 style="color:#004d40;">Fresh Seafood</h1>
        <p style="color:#004d40;">Daily catch • Premium seafood • Best prices</p>
    </div>
</section>



<section class="products-section">
    <div class="container">
        <div class="products-layout">

            <!-- MAIN PRODUCT LIST -->
            <main class="products-content">
                <div class="products-header">
                    <h2>Available Seafood</h2>
                </div>

                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <?php
                            $image_path = ltrim($product['product_image'], '/');
                            $product_url = "product.php?id=" . urlencode($product['product_id']);
                        ?>

                        <div class="product-card">
                            <div class="product-image">
                                <a href="<?= $product_url ?>">
                                    <img src="/<?= htmlspecialchars($image_path) ?>" 
                                         alt="<?= htmlspecialchars($product['product_name']) ?>">
                                </a>

                                <?php if (!empty($product['promo_price']) && $product['promo_price'] < $product['price_per_kg']): ?>
                                    <div class="product-badge sale">Promo</div>
                                <?php endif; ?>
                            </div>

                            <div class="product-content">
                                <h3><a href="<?= $product_url ?>"><?= htmlspecialchars($product['product_name']) ?></a></h3>

                                <div class="product-meta">
                                    <p><strong>Freshness:</strong> <?= ucfirst($product['freshness_level']) ?></p>
                                    <?php if (!empty($product['origin_country'])): ?>
                                        <p><strong>Origin:</strong> <?= htmlspecialchars($product['origin_country']) ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="product-price">
                                    <span class="current-price">RM<?= number_format($product['promo_price'] ?: $product['price_per_kg'], 2) ?>/kg</span>

                                    <?php if (!empty($product['promo_price']) && $product['promo_price'] < $product['price_per_kg']): ?>
                                        <span class="original-price">RM<?= number_format($product['price_per_kg'], 2) ?>/kg</span>
                                    <?php endif; ?>
                                </div>

                                <form method="POST"  action="cart.php" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <input type="hidden" name="name" value="<?= $product['product_name'] ?>">
                                    <input type="hidden" name="price" value="<?= $product['price_per_kg'] ?>">
                                    <input type="hidden" name="image" value="/<?= ltrim($product['product_image'], '/') ?>">
                                    <input type="hidden" name="action" value="add">
                                    <button type="submit" class="add-to-cart">Add to Cart</button>
                                </form>


                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>

            <!-- SIDEBAR -->
            <aside class="products-sidebar">
                <div class="filter-section">
                    <h3>Categories</h3>
                    <ul class="filter-list">
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="?category_id=<?= $cat['category_id'] ?>"
                                   class="<?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['category_id']) ? 'active' : '' ?>">
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <hr>

                    <h3>Freshness Level</h3>
                    <ul class="filter-list">
                        <li><a href="?fresh=fresh">Fresh</a></li>
                        <li><a href="?fresh=frozen">Frozen</a></li>
                        <li><a href="?fresh=live">Live</a></li>
                        <li><a href="?fresh=processed">Processed</a></li>
                    </ul>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
