<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Fetch categories for parent selection
$stmt = $pdo->query("SELECT category_id, name FROM categories WHERE status='active' ORDER BY name");
$allCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';
    $selectedParentId = $_POST['parent_id'] !== '' ? $_POST['parent_id'] : null;

    // Determine new category ID
    if ($selectedParentId) {
        // It's a subcategory
        $category_id = generateNextId($pdo, 'categories', 'category_id', 'SC', 5);
        $parent_id = $selectedParentId;
    } else {
        // It's a parent category
        $category_id = generateNextId($pdo, 'categories', 'category_id', 'C', 4);
        $parent_id = null;
    }

    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDirAbs = __DIR__ . '/../assets/images/categories/';
        $uploadDirWeb = 'assets/images/categories/';
        if (!is_dir($uploadDirAbs)) mkdir($uploadDirAbs, 0777, true);

        $filename = uniqid('cat_', true) . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDirAbs . $filename)) {
            $imagePath = $uploadDirWeb . $filename;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO categories (category_id, name, description, parent_id, status, image, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$category_id, $name, $description, $parent_id, $status, $imagePath]);

        $_SESSION['success'] = "Category added successfully!";
        header("Location: categories.php");
        exit;
    } catch (Throwable $e) {
        $_SESSION['error'] = "Error adding category: " . $e->getMessage();
    }
}

$categories = $pdo->query("SELECT category_id, name FROM categories WHERE parent_id IS NULL ORDER BY name")->fetchAll();
include '../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <h1>Add New Category</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>

            <div class="form-group">
                <label>Parent Category</label>
                <select name="parent_id">
                    <option value="">-- None (Top Level) --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add</button>
            <a href="categories.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
