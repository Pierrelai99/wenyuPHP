<?php
session_start();

// Only logged-in customers can access
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../public/login.php");
    exit();
}

require_once "../includes/db.php";

$user_code = $_SESSION['user_code'];

/* ---------------------------------------------------------
   1. REMOVE ITEM FROM WISHLIST
---------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $stmt = $pdo->prepare("DELETE FROM seafood_wishlist WHERE wishlist_id = ? AND user_code = ?");
    $stmt->execute([$_POST['remove_id'], $user_code]);

    $_SESSION['success'] = "Item removed from wishlist.";
    header("Location: wishlist.php");
    exit();
}
/* ---------------------------------------------------------
   1A. ADD ITEM TO WISHLIST
---------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {

    $product_id = intval($_POST['product_id']);

    // Prevent duplicates (your table has UNIQUE constraint)
    $stmt = $pdo->prepare("INSERT IGNORE INTO seafood_wishlist (user_code, product_id) VALUES (?, ?)");
    $stmt->execute([$user_code, $product_id]);

    $_SESSION['success'] = "Added to wishlist ❤️";
    header("Location: wishlist.php");
    exit();
}

/* ---------------------------------------------------------
   2. LOAD WISHLIST ITEMS
---------------------------------------------------------- */
$stmt = $pdo->prepare("
    SELECT 
        w.wishlist_id,
        p.product_id,
        p.product_name,
        p.product_image,
        p.price_per_kg,
        p.promo_price,
        p.status
    FROM seafood_wishlist w
    JOIN seafood_products p ON w.product_id = p.product_id
    WHERE w.user_code = ?
");
$stmt->execute([$user_code]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------------------------------------------------------
   PAGE PROPERTIES
---------------------------------------------------------- */
$page_title = "My Wishlist";
$show_breadcrumb = true;
$breadcrumb_items = [
    ["url" => "dashboard.php", "title" => "Dashboard"],
    ["url" => "#", "title" => "Wishlist"]
];

include "../includes/header.php";
?>

<section class="wishlist-section">
    <div class="container">
        <h1>💖 My Wishlist</h1>
        <p>Save your favorite seafood for faster reorders!</p>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <div class="empty-box">
                <p>No items in wishlist yet.</p>
                <a href="../public/products.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>

            <div class="wishlist-grid">
                <?php foreach ($items as $item): ?>
                    <div class="wishlist-card">

                        <div class="wishlist-img">
                            <img src="../<?= htmlspecialchars($item['product_image']) ?>" alt="">
                        </div>

                        <div class="wishlist-info">
                            <h3><?= htmlspecialchars($item['product_name']) ?></h3>

                            <?php if (!empty($item['promo_price'])): ?>
                                <p class="price">
                                    <span class="promo">RM <?= number_format($item['promo_price'], 2) ?>/kg</span>
                                    <span class="old">RM <?= number_format($item['price_per_kg'], 2) ?>/kg</span>
                                </p>
                            <?php else: ?>
                                <p class="price">RM <?= number_format($item['price_per_kg'], 2) ?>/kg</p>
                            <?php endif; ?>

                            <div class="card-actions">

                                <!-- ADD TO CART -->
                                <form method="POST" action="../public/cart.php">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <input type="hidden" name="name" value="<?= htmlspecialchars($item['product_name']) ?>">
                                    <input type="hidden" name="price" value="<?= $item['promo_price'] ?: $item['price_per_kg'] ?>">
                                    <input type="hidden" name="image" value="<?= htmlspecialchars($item['product_image']) ?>">
                                    <button class="btn btn-primary">Add to Cart</button>
                                </form>

                                <!-- REMOVE -->
                                <form method="POST" onsubmit="return confirm('Remove this item?');">
                                    <input type="hidden" name="remove_id" value="<?= $item['wishlist_id'] ?>">
                                    <button class="btn btn-danger">Remove</button>
                                </form>

                            </div>

                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>
</section>

<style>
.wishlist-section { padding: 40px 0; }
.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
.wishlist-card {
    background: #fff;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.wishlist-img img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
}
.wishlist-info h3 {
    margin: 10px 0 5px;
}
.price { font-size: 16px; margin-bottom: 10px; }
.price .promo { font-weight: bold; color: #d9534f; margin-right: 5px; }
.price .old { text-decoration: line-through; color: #777; }
.card-actions { display:flex; gap:10px; }
.btn-danger {
    background: #dc3545; 
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor:pointer;
}
.empty-box {
    text-align:center;
    padding:40px;
    background:#fff;
    border-radius:10px;
}
</style>

<?php include "../includes/footer.php"; ?>
