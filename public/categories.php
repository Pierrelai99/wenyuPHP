<?php
session_start();
require_once '../includes/db.php';

// Fetch all main categories (used in sidebar/menu)
$main_categories = $pdo->query("SELECT * FROM categories WHERE parent_id IS NULL AND status = 'active'")->fetchAll();

include '../includes/header.php';
?>

<section class="categories-section">
    <div class="container">
        <h2>Main Categories</h2>
        <div class="main-categories">
            <?php foreach ($main_categories as $cat): ?>
                <div class="category-card">
                    <a href="subcategories.php?parent_id=<?= urlencode($cat['category_id']) ?>">
                        <?php if (!empty($cat['image'])): ?>
                            <img src="/<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($cat['name']) ?></h3>
                        <p><?= htmlspecialchars($cat['description']) ?></p>
                        <span class="btn btn-outline">View Subcategories</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

