<?php
session_start();

// Check admin login
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/login.php');
    exit();
}

require_once '../includes/db.php';

/* ---------------------------------------------------------
   1. DELETE CATEGORY
--------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);

    try {
        // Check subcategories
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM seafood_categories WHERE parent_id = ?");
        $stmt->execute([$delete_id]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Cannot delete: Category has subcategories.");
        }

        // Check assigned products
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM seafood_products WHERE category_id = ?");
        $stmt->execute([$delete_id]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Cannot delete: Category has products assigned.");
        }

        // Load category image
        $stmt = $pdo->prepare("SELECT category_image FROM seafood_categories WHERE category_id = ?");
        $stmt->execute([$delete_id]);
        $imgPath = $stmt->fetchColumn();

        if ($imgPath && file_exists(__DIR__ . '/../' . $imgPath)) {
            unlink(__DIR__ . '/../' . $imgPath);
        }

        // Delete category
        $stmt = $pdo->prepare("DELETE FROM seafood_categories WHERE category_id = ?");
        $stmt->execute([$delete_id]);

        $_SESSION['success'] = "Category deleted successfully.";

    } catch (Throwable $e) {
        $_SESSION['error'] = "Error deleting category: " . $e->getMessage();
    }

    header("Location: categories.php");
    exit();
}

/* ---------------------------------------------------------
   2. FETCH ALL CATEGORIES (hierarchical)
--------------------------------------------------------- */
$stmt = $pdo->query("SELECT * FROM seafood_categories ORDER BY parent_id ASC, category_name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group into hierarchy
$grouped = [];
foreach ($categories as $cat) {
    $grouped[$cat['parent_id']][] = $cat;
}

/* ---------------------------------------------------------
   3. Recursive Display Function
--------------------------------------------------------- */
function renderCategories($parent_id, $grouped, $level = 0)
{
    if (!isset($grouped[$parent_id])) return;

    foreach ($grouped[$parent_id] as $cat) {

        echo "<tr>
                <td>{$cat['category_id']}</td>
                <td>" . str_repeat("&nbsp;&nbsp;&nbsp;— ", $level) . htmlspecialchars($cat['category_name']) . "</td>
                <td>" . htmlspecialchars($cat['description']) . "</td>
                <td>";

        if ($cat['category_image']) {
            echo "<img src='../" . htmlspecialchars($cat['category_image']) . "' style='width:80px; border-radius:6px;'>";
        } else {
            echo "<em>No image</em>";
        }

        echo "</td>
                <td>" . ucfirst($cat['status']) . "</td>
                <td>
                    <a href='categories_edit.php?id={$cat['category_id']}' class='btn btn-small btn-edit'>Edit</a>

                    <form method='POST' action='categories.php' style='display:inline;' 
                        onsubmit=\"return confirm('Are you sure to delete this category?');\">
                        <input type='hidden' name='delete_id' value='{$cat['category_id']}'>
                        <button type='submit' class='btn btn-small btn-delete'>Delete</button>
                    </form>
                </td>
            </tr>";

        renderCategories($cat['category_id'], $grouped, $level + 1);
    }
}

/* ---------------------------------------------------------
   PAGE SETTINGS
--------------------------------------------------------- */
$page_title = "Manage Categories";
include '../includes/header.php';
?>

<section class="admin-section categories-management">
    <div class="container">
        <h1>Manage Categories</h1>

        <!-- Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <a href="categories_add.php" class="btn btn-primary">+ Add Category</a>

        <table class="admin-table" style="margin-top:20px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th style="width:150px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php renderCategories(null, $grouped); ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
