<?php
session_start();
require_once '../includes/db.php';

$parent_id = isset($_GET['parent_id']) ? $_GET['parent_id'] : null;
$parent_category = null;
$subcategories = [];

if ($parent_id) {
    // Get parent category
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt->execute([$parent_id]);
    $parent_category = $stmt->fetch();

    // Get subcategories
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE parent_id = ? AND status = 'active'");
    $stmt->execute([$parent_id]);
    $subcategories = $stmt->fetchAll();
}

include '../includes/header.php';
?>

<section class="subcategories-section">
    <div class="container">
        <?php if ($parent_category): ?>
            <h2>Subcategories of <?= htmlspecialchars($parent_category['name']) ?></h2>
            <div class="subcategories-grid">
                <?php foreach ($subcategories as $sub): ?>
                    <div class="subcategory-card">
                        <a href="products.php?category_id=<?= urlencode($sub['category_id']) ?>">
                            <?php if (!empty($sub['image'])): ?>
                                <img src="/<?= htmlspecialchars($sub['image']) ?>" alt="<?= htmlspecialchars($sub['name']) ?>">
                            <?php endif; ?>
                            <h4><?= htmlspecialchars($sub['name']) ?></h4>
                            <p><?= htmlspecialchars($sub['description']) ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Category not found.</p>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>