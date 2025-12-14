<?php
session_start();

if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../public/login.php");
    exit();
}

require_once "../includes/db.php";

$user_code = $_SESSION['user_code'];

/* ---------------------------------------------------------
   1. LOAD USER REVIEWS
---------------------------------------------------------- */
$stmt = $pdo->prepare("
    SELECT 
        r.review_id,
        r.rating,
        r.review_title,
        r.review_text,
        r.review_status,
        r.created_on,
        p.product_id,
        p.product_name,
        p.product_image
    FROM seafood_reviews r
    JOIN seafood_products p ON r.product_id = p.product_id
    WHERE r.user_code = ?
    ORDER BY r.created_on DESC
");
$stmt->execute([$user_code]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------------------------------------------------------
   PAGE SETTINGS
---------------------------------------------------------- */
$page_title = "My Reviews";
$show_breadcrumb = true;
$breadcrumb_items = [
    ["url" => "dashboard.php", "title" => "Dashboard"],
    ["url" => "#", "title" => "My Reviews"]
];

include "../includes/header.php";
?>

<section class="reviews-section">
    <div class="container">

        <h1>⭐ My Reviews</h1>
        <p>Share your experience to help other seafood lovers!</p>

        <?php if (empty($reviews)): ?>
            <div class="empty-box">
                <p>You have not written any reviews yet.</p>
                <a href="../public/products.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>

            <div class="reviews-grid">

                <?php foreach ($reviews as $r): ?>
                    <div class="review-card">

                        <!-- Product Image -->
                        <div class="review-img">
                            <a href="../public/product.php?id=<?= $r['product_id'] ?>">
                                <img src="../<?= htmlspecialchars($r['product_image']) ?>" alt="">
                            </a>
                        </div>

                        <!-- Review Content -->
                        <div class="review-info">

                            <h3>
                                <a href="../public/product.php?id=<?= $r['product_id'] ?>">
                                    <?= htmlspecialchars($r['product_name']) ?>
                                </a>
                            </h3>

                            <!-- Rating -->
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $r['rating']): ?>
                                        <i class="fas fa-star filled"></i>
                                    <?php else: ?>
                                        <i class="fas fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>

                            <!-- Title & Text -->
                            <?php if (!empty($r['review_title'])): ?>
                                <h4 class="review-title"><?= htmlspecialchars($r['review_title']) ?></h4>
                            <?php endif; ?>

                            <p class="review-text"><?= nl2br(htmlspecialchars($r['review_text'])) ?></p>

                            <!-- Status -->
                            <span class="status status-<?= $r['review_status'] ?>">
                                <?= ucfirst($r['review_status']) ?>
                            </span>

                            <p class="review-date">Reviewed on <?= date("d M Y", strtotime($r['created_on'])) ?></p>

                        </div>

                    </div>
                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</section>

<style>
.reviews-section { padding:40px 0; }
.reviews-grid { display:flex; flex-direction:column; gap:20px; }

.review-card {
    display:flex;
    background:#fff;
    padding:20px;
    border-radius:10px;
    gap:20px;
    box-shadow:0 2px 4px rgba(0,0,0,0.1);
}

.review-img img {
    width:120px;
    height:120px;
    border-radius:8px;
    object-fit:cover;
}

.review-info { flex:1; }

.stars i {
    color:#ccc;
}
.stars .filled {
    color:#FFD700;
}

.review-title { font-size:18px; margin-top:5px; font-weight:bold; }
.review-text { margin:10px 0; }
.review-date { color:#777; font-size:13px; margin-top:10px; }

.status {
    display:inline-block;
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    margin-top:5px;
}

.status-pending { background:#fff3cd; color:#856404; }
.status-approved { background:#d4edda; color:#155724; }
.status-rejected { background:#f8d7da; color:#721c24; }
</style>

<?php include "../includes/footer.php"; ?>
