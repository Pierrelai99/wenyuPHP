<?php
session_start();
require_once "../includes/db.php";

// Validate product ID
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = intval($_GET['id']);

/* ---------------------------------------------------------
   1. LOAD PRODUCT DETAILS
---------------------------------------------------------- */
$stmt = $pdo->prepare("
    SELECT p.*, c.category_name
    FROM seafood_products p
    LEFT JOIN seafood_categories c ON p.category_id = c.category_id
    WHERE p.product_id = ? AND p.status = 'available'
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: products.php");
    exit();
}

/* ---------------------------------------------------------
   2. CHECK IF IN WISHLIST
---------------------------------------------------------- */
$in_wishlist = false;
if (isset($_SESSION['user_code'])) {
    $check = $pdo->prepare("
        SELECT wishlist_id 
        FROM seafood_wishlist 
        WHERE user_code = ? AND product_id = ?
    ");
    $check->execute([$_SESSION['user_code'], $product_id]);
    $in_wishlist = $check->fetch() ? true : false;
}

/* ---------------------------------------------------------
   3. LOAD RELATED PRODUCTS
---------------------------------------------------------- */
$related_stmt = $pdo->prepare("
    SELECT product_id, product_name, price_per_kg, promo_price, product_image
    FROM seafood_products
    WHERE category_id = ? AND product_id != ? AND status = 'available'
    LIMIT 4
");
$related_stmt->execute([$product['category_id'], $product_id]);
$related = $related_stmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------------------------------------------------------
   PAGE SETTINGS
---------------------------------------------------------- */
$page_title = $product['product_name'];
$show_breadcrumb = true;
$breadcrumb_items = [
    ["url" => "products.php", "title" => "Products"],
    ["url" => "#", "title" => $product['product_name']]
];

include "../includes/header.php";
?>

<section class="product-details-section">
    <div class="container">

        <div class="product-layout">

            <!-- PRODUCT IMAGE -->
            <div class="product-image">
                <img src="../<?= htmlspecialchars($product['product_image']) ?>" alt="">
            </div>

            <!-- PRODUCT INFO -->
            <div class="product-info">

                <h1><?= htmlspecialchars($product['product_name']) ?></h1>
                <p class="category">Category: <strong><?= htmlspecialchars($product['category_name']) ?></strong></p>

                <!-- PRICE -->
                <div class="price-box">
                    <?php if ($product['promo_price']): ?>
                        <span class="promo-price">RM <?= number_format($product['promo_price'], 2) ?>/kg</span>
                        <span class="old-price">RM <?= number_format($product['price_per_kg'], 2) ?>/kg</span>
                    <?php else: ?>
                        <span class="normal-price">RM <?= number_format($product['price_per_kg'], 2) ?>/kg</span>
                    <?php endif; ?>
                </div>

                <!-- STOCK -->
                <p class="stock">Stock available: <strong><?= $product['stock_kg'] ?> kg</strong></p>

                <!-- SHORT DETAILS -->
                <ul class="product-meta">
                    <li><strong>Freshness:</strong> <?= ucfirst($product['freshness_level']) ?></li>
                    <li><strong>Origin:</strong> <?= htmlspecialchars($product['origin_country']) ?></li>
                    <?php if ($product['weight_per_unit']): ?>
                    <li><strong>Weight per unit:</strong> <?= $product['weight_per_unit'] ?> g</li>
                    <?php endif; ?>
                    <?php if ($product['harvest_date']): ?>
                    <li><strong>Harvested on:</strong> <?= $product['harvest_date'] ?></li>
                    <?php endif; ?>
                    <li><strong>Storage Temp:</strong> <?= htmlspecialchars($product['storage_temp']) ?></li>
                </ul>

                <!-- DESCRIPTION -->
                <h3>Description</h3>
                <p><?= nl2br(htmlspecialchars($product['product_desc'])) ?></p>

                <!-- ACTION BUTTONS -->
                <div class="action-buttons">

                    <!-- ADD TO CART -->
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <input type="hidden" name="name" value="<?= htmlspecialchars($product['product_name']) ?>">
                        <input type="hidden" name="price" value="<?= $product['promo_price'] ?: $product['price_per_kg'] ?>">
                        <input type="hidden" name="image" value="<?= htmlspecialchars($product['product_image']) ?>">

                        <label>Quantity (kg):</label>
                        <input type="number" name="qty" min="0.5" step="0.5" value="1" required>

                        <button class="btn btn-primary">Add to Cart</button>
                    </form>

                    <!-- WISHLIST BUTTON -->
                    <?php if (isset($_SESSION['user_code'])): ?>
                        
                        <?php if ($in_wishlist): ?>
                            <form method="POST" action="../../member/wishlist.php">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                <button class="btn btn-danger">❤️ Remove Wishlist</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="../../member/wishlist.php">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                <button class="btn btn-secondary">🤍 Add to Wishlist</button>
                            </form>
                        <?php endif; ?>

                    <?php else: ?>
                        <a href="login.php" class="btn btn-secondary">Login to Save ❤️</a>
                    <?php endif; ?>

                </div>

            </div> <!-- end product-info -->

        </div> <!-- end layout -->

        <!-- RELATED PRODUCTS -->
        <h2 class="related-title">🔎 Related Products</h2>
        <div class="related-grid">

            <?php foreach ($related as $r): ?>
                <div class="related-card">
                    <a href="product.php?id=<?= $r['product_id'] ?>">
                        <img src="../<?= htmlspecialchars($r['product_image']) ?>" alt="">
                        <h4><?= htmlspecialchars($r['product_name']) ?></h4>

                        <?php if ($r['promo_price']): ?>
                            <p class="price">
                                <span class="promo">RM <?= number_format($r['promo_price'], 2) ?></span>
                                <span class="old">RM <?= number_format($r['price_per_kg'], 2) ?></span>
                            </p>
                        <?php else: ?>
                            <p class="price">RM <?= number_format($r['price_per_kg'], 2) ?></p>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>

<style>
.product-layout { display:flex; gap:30px; margin-top:20px; }
.product-image img { width:400px; border-radius:10px; }
.product-info { flex:1; }
.price-box { margin:10px 0; font-size:22px; }
.promo-price { color:#d9534f; font-weight:bold; margin-right:10px; }
.old-price { text-decoration:line-through; color:#777; }
.normal-price { font-size:24px; font-weight:bold; }
.product-meta { list-style:none; padding:0; margin-top:10px; }
.product-meta li { margin-bottom:5px; }
.action-buttons { margin-top:20px; display:flex; gap:15px; align-items:center; flex-wrap:wrap; }
.related-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:20px; margin-top:30px; }
.related-card img { width:100%; border-radius:8px; }
.related-card h4 { margin-top:10px; font-size:16px; }
</style>

<?php include "../includes/footer.php"; ?>
