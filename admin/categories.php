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
    $delete_id = trim($_POST['delete_id']);

    try {
        // Block delete if it has subcategories
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE parent_id = ?");
        $stmt->execute([$delete_id]);
        if ((int)$stmt->fetchColumn() > 0) {
            throw new Exception("Cannot delete: category has subcategories.");
        }

        // Block delete if products exist
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
        $stmt->execute([$delete_id]);
        if ((int)$stmt->fetchColumn() > 0) {
            throw new Exception("Cannot delete: category has products assigned.");
        }

        // Delete image file
        $stmt = $pdo->prepare("SELECT image FROM categories WHERE category_id = ?");
        $stmt->execute([$delete_id]);
        $imgPath = $stmt->fetchColumn();
        if ($imgPath && file_exists(__DIR__ . '/../' . $imgPath)) {
            unlink(__DIR__ . '/../' . $imgPath);
        }

        $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->execute([$delete_id]);

        $_SESSION['success'] = "Category deleted successfully!";
    } catch (Throwable $e) {
        $_SESSION['error'] = "Error deleting category: " . $e->getMessage();
    }

    header("Location: categories.php");
    exit;
}

// --- Fetch categories (group by parent_id) ---
$stmt = $pdo->query("SELECT * FROM categories ORDER BY parent_id ASC, name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group into hierarchy
$categoriesByParent = [];
foreach ($categories as $cat) {
    $categoriesByParent[$cat['parent_id']][] = $cat;
}

// Recursive display
function displayCategories($parentId, $categoriesByParent, $level = 0) {
    if (!isset($categoriesByParent[$parentId])) return;

    foreach ($categoriesByParent[$parentId] as $cat) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($cat['category_id']) . "</td>";
        echo "<td>" . str_repeat("— ", $level) . htmlspecialchars($cat['name']) . "</td>";
        echo "<td>" . htmlspecialchars($cat['description']) . "</td>";
        echo "<td>";
        if (!empty($cat['image'])) {
            echo "<img src='../" . htmlspecialchars($cat['image']) . "' style='max-width:80px'>";
        } else {
            echo "No image";
        }
        echo "</td>";
        echo "<td>" . htmlspecialchars($cat['status']) . "</td>";
        echo "<td>
                <a href='categories_edit.php?id={$cat['category_id']}' class='btn btn-small btn-edit'>Edit</a>
                <form method='post' action='categories.php' style='display:inline;' 
                      onsubmit=\"return confirm('Delete this category?');\">
                    <input type='hidden' name='delete_id' value='{$cat['category_id']}'>
                    <button type='submit' class='btn btn-small btn-delete'>Delete</button>
                </form>
              </td>";
        echo "</tr>";

        // Show children
        displayCategories($cat['category_id'], $categoriesByParent, $level + 1);
    }
}




// Page variables
$page_title = "Manage Categories";
$page_description = "Add, edit, and delete product categories";
include '../includes/header.php';
?>

<section class="admin-section categories-management">
    <div class="container">
            <h1>Manage Categories</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

      <a href="categories_add.php" class="btn btn-primary">+ Add New</a>

       <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($categories): ?>
                    <?php displayCategories(null, $categoriesByParent); ?>
                <?php else: ?>
                    <tr><td colspan="5">No categories found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

