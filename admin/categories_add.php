<?php
session_start();

// Check if admin
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/login.php');
    exit();
}

require_once '../includes/db.php';

/* ---------------------------------------------------------
   FETCH CATEGORIES FOR DROPDOWN (TOP LEVEL + SUB)
--------------------------------------------------------- */
$stmt = $pdo->query("SELECT category_id, category_name FROM seafood_categories ORDER BY category_name ASC");
$allCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------------------------------------------------------
   HANDLE CATEGORY ADD
--------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['category_name']);
    $description = trim($_POST['description']);
    $status = ($_POST['status'] === 'inactive') ? 'inactive' : 'active';
    $parent_id = ($_POST['parent_id'] !== '') ? intval($_POST['parent_id']) : null;

    /* -------------------------
       HANDLE IMAGE UPLOAD
    ----------------------------*/
    $image_path = null;

    if (!empty($_FILES['category_image']['name'])) {
        $uploadDirAbs = __DIR__ . '/../assets/images/categories/';
        $uploadDirWeb = 'assets/images/categories/';

        if (!is_dir($uploadDirAbs)) {
            mkdir($uploadDirAbs, 0777, true);
        }

        $filename = uniqid('cat_', true) . "." . pathinfo($_FILES['category_image']['name'], PATHINFO_EXTENSION);
        $fullpath = $uploadDirAbs . $filename;

        if (move_uploaded_file($_FILES['category_image']['tmp_name'], $fullpath)) {
            $image_path = $uploadDirWeb . $filename;
        }
    }

    /* -------------------------
       INSERT INTO DATABASE
    ----------------------------*/
    try {
        $stmt = $pdo->prepare("
            INSERT INTO seafood_categories 
            (category_name, description, parent_id, status, category_image, created_on) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $name,
            $description,
            $parent_id,
            $status,
            $image_path
        ]);

        $_SESSION['success'] = "Category added successfully!";
        header("Location: categories.php");
        exit();

    } catch (Throwable $e) {
        $_SESSION['error'] = "Error adding category: " . $e->getMessage();
    }
}

$page_title = "Add New Category";
include '../includes/header.php';
?>


<section class="admin-section">
    <div class="container">
        <h1>Add New Category</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="admin-form">

            <!-- CATEGORY NAME -->
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="category_name" required class="form-control">
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <!-- PARENT CATEGORY -->
            <div class="form-group">
                <label>Parent Category</label>
                <select name="parent_id" class="form-control">
                    <option value="">-- None (Top Level) --</option>
                    <?php foreach ($allCategories as $c): ?>
                        <option value="<?= $c['category_id'] ?>">
                            <?= htmlspecialchars($c['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- IMAGE -->
            <div class="form-group">
                <label>Category Image</label>
                <input type="file" name="category_image" accept="image/*" class="form-control">
            </div>

            <!-- STATUS -->
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- BUTTONS -->
            <button type="submit" class="btn btn-primary">Add Category</button>
            <a href="categories.php" class="btn btn-secondary">Cancel</a>

        </form>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
