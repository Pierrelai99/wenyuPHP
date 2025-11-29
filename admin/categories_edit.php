<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['error'] = "No category selected.";
    header("Location: categories.php");
    exit;
}

// Fetch categories for parent selection (exclude itself)
$stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ? AND status='active' ORDER BY name");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$category) { $_SESSION['error'] = "Category not found"; 
    header("Location: categories.php"); 
    exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';
    $parent_id = $_POST['parent_id'] !== '' ? $_POST['parent_id'] : null;
    $imagePath = $category['image'];

    if (!empty($_FILES['image']['name'])) {
        $uploadDirAbs = __DIR__ . '/../assets/images/categories/';
        $uploadDirWeb = 'assets/images/categories/';
        if (!is_dir($uploadDirAbs)) mkdir($uploadDirAbs, 0777, true);

        // remove old
        if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)) 
            unlink(__DIR__ . '/../' . $imagePath);

        $filename = uniqid('cat_', true) . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDirAbs . $filename)) {
            $imagePath = $uploadDirWeb . $filename;
        }
    }

    try {
    $stmt = $pdo->prepare("UPDATE categories 
                           SET name=?, description=?, parent_id=?, status=?, image=?, updated_at=NOW() 
                           WHERE category_id=?");
    $stmt->execute([$name, $description, $parent_id, $status, $imagePath, $id]);

    $_SESSION['success'] = "Category updated successfully!";
    header("Location: categories.php");
    exit;
    } catch (Throwable $e) {
    $_SESSION['error'] = "Error updating category: " . $e->getMessage();
    }
}

$categories = $pdo->prepare("SELECT category_id, name FROM categories WHERE category_id != ? AND parent_id IS NULL ORDER BY name");
$categories->execute([$id]);
$categories = $categories->fetchAll();

include '../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <h1>Edit Category</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Category ID</label>
                <input type="text" value="<?= htmlspecialchars($category['category_id']) ?>" disabled>
            </div>
            
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Parent Category</label>
                <select name="parent_id">
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>" <?= $c['category_id']==$category['parent_id']?'selected':'' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

             <div class="form-group">
                <label>Current Image</label><br>
                <?php if ($category['image']): ?>
                    <img src="../<?= htmlspecialchars($category['image']) ?>" style="max-width:120px"><br>
                <?php else: ?> No image <?php endif; ?>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?= $category['status']=='active'?'selected':'' ?>>Active</option>
                    <option value="inactive" <?= $category['status']=='inactive'?'selected':'' ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="categories.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
