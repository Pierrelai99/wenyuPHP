<?php
session_start();

// Check admin
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/login.php');
    exit();
}

require_once '../includes/db.php';

/* ---------------------------------------------------------
   1. VALIDATE CATEGORY ID
--------------------------------------------------------- */
if (!isset($_GET['id'])) {
    header("Location: categories.php");
    exit();
}

$category_id = intval($_GET['id']);

/* ---------------------------------------------------------
   2. LOAD EXISTING CATEGORY
--------------------------------------------------------- */
$stmt = $pdo->prepare("SELECT * FROM seafood_categories WHERE category_id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    $_SESSION['error'] = "Category not found.";
    header("Location: categories.php");
    exit();
}

/* ---------------------------------------------------------
   3. LOAD ALL CATEGORIES FOR PARENT SELECTION
--------------------------------------------------------- */
$allCategories = $pdo->query("
    SELECT category_id, category_name 
    FROM seafood_categories 
    ORDER BY category_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* ---------------------------------------------------------
   4. HANDLE FORM SUBMIT (UPDATE CATEGORY)
--------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['category_name']);
    $description = trim($_POST['description']);
    $status = ($_POST['status'] === 'inactive') ? 'inactive' : 'active';

    // Parent category (cannot set itself as parent)
    $parent_id = ($_POST['parent_id'] !== '') ? intval($_POST['parent_id']) : null;
    if ($parent_id == $category_id) {
        $parent_id = null;
    }

    // Image handling
    $image_path = $category['category_image']; // keep old image

    if (!empty($_FILES['category_image']['name'])) {
        $uploadDirAbs = __DIR__ . '/../assets/images/categories/';
        $uploadDirWeb = 'assets/images/categories/';

        if (!is_dir($uploadDirAbs)) {
            mkdir($uploadDirAbs, 0777, true);
        }

        $filename = uniqid('cat_', true) . "." . pathinfo($_FILES['category_image']['name'], PATHINFO_EXTENSION);
        $fullpath = $uploadDirAbs . $filename;

        if (move_uploaded_file($_FILES['category_image']['tmp_name'], $fullpath)) {

            // Delete old image if exists
            if (!empty($category['category_image']) && file_exists(__DIR__ . '/../' . $category['category_image'])) {
                unlink(__DIR__ . '/../' . $category['category_image']);
            }

            $image_path = $uploadDirWeb . $filename;
        }
    }

    /* ---------------------------------------------------------
       UPDATE CATEGORY IN DATABASE
    --------------------------------------------------------- */
    try {
        $stmt = $pdo->prepare("
            UPDATE seafood_categories 
            SET category_name = ?, description = ?, parent_id = ?, 
                status = ?, category_image = ?, updated_on = NOW()
            WHERE category_id = ?
        ");

        $stmt->execute([
            $name,
            $description,
            $parent_id,
            $status,
            $image_path,
            $category_id
        ]);

        $_SESSION['success'] = "Category updated successfully!";
        header("Location: categories.php");
        exit();

    } catch (Throwable $e) {
        $_SESSION['error'] = "Error updating category: " . $e->getMessage();
    }
}

$page_title = "Edit Category";
include '../includes/header.php';
?>


<section class="admin-section">
    <div class="container">

        <h1>Edit Category</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="admin-form">

            <!-- NAME -->
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="category_name" value="<?= htmlspecialchars($category['category_name']) ?>" required>
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($category['description']) ?></textarea>
            </div>

            <!-- PARENT CATEGORY -->
            <div class="form-group">
                <label>Parent Category</label>
                <select name="parent_id">
                    <option value="">-- None (Top Level) --</option>

                    <?php foreach ($allCategories as $c): ?>
                        <?php if ($c['category_id'] != $category_id): ?>
                            <option value="<?= $c['category_id'] ?>"
                                <?= ($category['parent_id'] == $c['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['category_name']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- IMAGE -->
            <div class="form-group">
                <label>Category Image</label>

                <?php if (!empty($category['category_image'])): ?>
                    <div>
                        <img src="../<?= $category['category_image'] ?>" style="max-width:100px; border-radius:5px;">
                    </div>
                <?php endif; ?>

                <input type="file" name="category_image" accept="image/*">
                <small>Leave empty to keep current image</small>
            </div>

            <!-- STATUS -->
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?= $category['status']=='active'?'selected':'' ?>>Active</option>
                    <option value="inactive" <?= $category['status']=='inactive'?'selected':'' ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="categories.php" class="btn btn-secondary">Cancel</a>

        </form>

    </div>
</section>


<?php include '../includes/footer.php'; ?>
