<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = $_GET['id'];

function deleteFileIfExists($path) {
    $fullPath = __DIR__ . '/../' . $path;
    if ($path && file_exists($fullPath) && strpos($path, 'no-image.png') === false) {
        unlink($fullPath);
    }
}

//Helpers for fileUpload
function handleFileUpload($file, $uploadDirAbs, $uploadDirWeb) {
    // Only proceed if a file was actually chosen
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Basic allowlist (optional but safer)
    $allowed = ['jpg','jpeg','png','gif','webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        return null;
    }

    // Ensure folder exists
    if (!is_dir($uploadDirAbs)) {
        @mkdir($uploadDirAbs, 0775, true);
    }

    // Generate filename and move
    $filename  = uniqid('prod_', true) . '.' . $ext;
    $targetAbs = $uploadDirAbs . $filename;
    if (move_uploaded_file($file['tmp_name'], $targetAbs)) {
        return $uploadDirWeb . $filename;
    }

    return null;
}

// --- Fetch categories for dropdown ---
$categories = $pdo->query("SELECT category_id, name FROM categories WHERE status = 'active' ORDER BY name")->fetchAll();

// --- Fectch product ---
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product){
    $_SESSION['error'] = "Product not found.";
    header('Location: products.php');
    exit();
}

// --- Fetch gallery images ---
$galleryStmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY image_id ASC");
$galleryStmt->execute([$product_id]);
$galleryImages = $galleryStmt->fetchAll(PDO::FETCH_ASSOC);


// Handle POST uodate
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $sale_price = ($_POST['sale_price'] === '' ? null : (float)$_POST['sale_price']);
    $stock_quantity = (int)($_POST['stock_quantity'] ?? 0);
    $status = ($_POST['status'] ?? 'inactive') === 'active' ? 'active' : 'inactive';
    $product_features = trim($_POST['product_features'] ?? '');
    $product_specifications = trim($_POST['product_specifications'] ?? '');
    $category_id = $_POST['category_id'] ?? '';

    if ($sale_price === null) $sale_price = $price;

    $uploadDirAbs = rtrim(__DIR__ . '/../assets/images/products/', '/\\') . DIRECTORY_SEPARATOR;
    $uploadDirWeb = 'assets/images/products/';

    // Update main image if uploaded
    $main_image_path = $product['image']; // keep existing if not updated
    if (!empty($_FILES['main_image']['name'])) {
        // delete old file if exists
        deleteFileIfExists($product['image']);
        
        // upload new one
        $uploaded_path = handleFileUpload($_FILES['main_image'], $uploadDirAbs, $uploadDirWeb);
        if ($uploaded_path) {
            $main_image_path = ltrim($uploaded_path, '/');
        }
    }


    try {
         $pdo->beginTransaction();

         // --- Handle Remove Images FIRST (before adding new ones) ---
        if (!empty($_POST['remove_images'])) {
            foreach ($_POST['remove_images'] as $imgId) {
                // Get image path before deleting
                $stmtGetPath = $pdo->prepare("SELECT image_path FROM product_images WHERE image_id = ? AND product_id = ?");
                $stmtGetPath->execute([$imgId, $product_id]);
                $imgPath = $stmtGetPath->fetchColumn();

                if ($imgPath) {
                    deleteFileIfExists($imgPath);
                }

                // delete from DB
                $stmtDel = $pdo->prepare("DELETE FROM product_images WHERE image_id = ? AND product_id = ?");
                $stmtDel->execute([$imgId, $product_id]);
            }
        }

        // Update product
        $stmt =  $pdo->prepare("
            UPDATE products SET
                name = :name,
                description = :description,
                price = :price,
                sale_price = :sale_price,
                stock_quantity = :stock_quantity,
                status = :status,
                product_features = :product_features,
                product_specifications = :product_specifications,
                category_id = :category_id,
                image = :image,
                updated_at = NOW()
            WHERE product_id = :id
        ");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':sale_price' => $sale_price,
            ':stock_quantity' => $stock_quantity,
            ':status' => $status,
            ':product_features' => $product_features,
            ':product_specifications' => $product_specifications,
            ':category_id' => $category_id,
            ':image' => ltrim($main_image_path ?? 'assets/images/no-image.png', '/'),
            ':id' => $product_id
        ]);

        // Add new gallery images if uploaded
       if (!empty($_FILES['gallery']['name'][0])) {
            foreach ($_FILES['gallery']['name'] as $key => $galleryName) {
                if (empty($galleryName)) continue; // Skip empty files
                
                $file = [
                    'name'     => $_FILES['gallery']['name'][$key],
                    'type'     => $_FILES['gallery']['type'][$key],
                    'tmp_name' => $_FILES['gallery']['tmp_name'][$key],
                    'error'    => $_FILES['gallery']['error'][$key],
                    'size'     => $_FILES['gallery']['size'][$key]
                ];
                
                $galleryPath = handleFileUpload($file, $uploadDirAbs, $uploadDirWeb);
                if ($galleryPath) {
                    $stmtImg = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    $stmtImg->execute([$product_id, ltrim($galleryPath, '/')]);
                }
            }
        }

        $pdo->commit();

        $_SESSION['success'] = "Product updated successfully!";
        header("Location: products.php");
        exit;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $_SESSION['error'] = "Error updating product: " . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <h1>Edit Product</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="product_edit.php?id=<?= $product_id ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Price (RM)</label>
                <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
            </div>

            <div class="form-group">
                <label>Sale Price (RM)</label>
                <input type="number" step="0.01" name="sale_price" value="<?= $product['sale_price'] ?>">
            </div>

            <div class="form-group">
                <label for="stock_quantity">Stock Quantity *</label>
                <input type="number" id="stock_quantity" name="stock_quantity" min="0" 
                       value="<?= htmlspecialchars($product['stock_quantity'] ?? 0) ?>" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Features</label>
                <textarea name="product_features" rows="3"><?= htmlspecialchars($product['product_features']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Specifications</label>
                <textarea name="product_specifications" rows="4"><?= htmlspecialchars($product['product_specifications']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Main Image</label>
                <?php if ($product['image']): ?>
                    <img src="/<?= htmlspecialchars($product['image']) ?>" alt="" style="max-width:150px;display:block;">
                <?php endif; ?>
                <input type="file" name="main_image" accept="image/*">
            </div>

            <h3>Gallery Images (Drag & Drop to Reorder)</h3>
            <ul id="gallery-list" style="list-style:none; padding:0; display:flex; flex-wrap:wrap; gap:15px;">
                <?php foreach ($galleryImages as $img): ?>
                    <li data-id="<?= $img['image_id'] ?>" style="border:1px solid #ccc; padding:10px; background:#fafafa; cursor:move;">
                     <img src="/<?= htmlspecialchars($img['image_path']) ?>" alt="" style="max-width:120px; display:block; margin-bottom:5px;">
                <label>
                  <input type="checkbox" name="remove_images[]" value="<?= $img['image_id'] ?>"> Remove
                </label>
                </li>
               <?php endforeach; ?>
            </ul>

            <?php if (empty($galleryImages)): ?>
                <p style="color:#666; font-style:italic;">No gallery images yet.</p>
            <?php endif; ?>

            <div class="form-group">
                <label for="gallery_images">Add More Gallery Images</label>
                <input type="file" id="gallery_images" name="gallery[]" accept="image/*" multiple style="display: none;">
                
                <div style="margin-bottom: 10px;">
                    <button type="button" id="select_files_btn" class="btn btn-secondary" style="margin-right: 10px;">
                        Choose Files
                    </button>
                    <button type="button" id="add_more_files_btn" class="btn btn-secondary" style="margin-right: 10px;">
                        Add More Files
                    </button>
                    <button type="button" id="clear_files_btn" class="btn btn-secondary">
                        Clear All
                    </button>
                </div>
                
                <div id="preview" style="margin-top:10px;">
                    <em>No files selected</em>
                </div>
                <small>JPG, PNG, WEBP, GIF. You can select multiple files at once or add more files in separate selections.</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="products.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(function() {
    $("#gallery-list").sortable({
        update: function() {
            let order = $(this).sortable("toArray", {attribute: "data-id"});
            $.post("product_edit.php?id=<?= $product_id ?>", { sort_order: order }, function(resp) {
                console.log("Sort update:", resp);
            });
        }
    });
});

// Gallery file selection script (same as in product_add.php)
const galleryInput = document.getElementById('gallery_images');
const preview = document.getElementById('preview');
const selectFilesBtn = document.getElementById('select_files_btn');
const addMoreFilesBtn = document.getElementById('add_more_files_btn');
const clearFilesBtn = document.getElementById('clear_files_btn');
let selectedFiles = [];

// Button event listeners
selectFilesBtn.addEventListener('click', () => {
    selectedFiles = []; // Clear existing files when "Choose Files" is clicked
    galleryInput.click();
});

addMoreFilesBtn.addEventListener('click', () => {
    galleryInput.click(); // Keep existing files when "Add More" is clicked
});

clearFilesBtn.addEventListener('click', () => {
    selectedFiles = [];
    updatePreview();
    updateFileInput();
});

// File input change handler
galleryInput.addEventListener('change', function(e) {
    Array.from(e.target.files).forEach(file => {
        const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
        if (!exists) {
            selectedFiles.push(file);
        }
    });
    
    updatePreview();
    updateFileInput();
});

function updatePreview() {
    preview.innerHTML = "";
    
    if (selectedFiles.length === 0) {
        preview.innerHTML = "<em>No files selected</em>";
        return;
    }
    
    selectedFiles.forEach((file, i) => {
        const div = document.createElement("div");
        div.style.marginBottom = "8px";
        div.style.padding = "8px";
        div.style.backgroundColor = "#f8f9fa";
        div.style.border = "1px solid #dee2e6";
        div.style.borderRadius = "4px";
        div.style.display = "flex";
        div.style.justifyContent = "space-between";
        div.style.alignItems = "center";
        
        const fileInfo = document.createElement("span");
        fileInfo.textContent = `${i+1}. ${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`;
        
        const removeBtn = document.createElement("button");
        removeBtn.type = "button";
        removeBtn.textContent = "×";
        removeBtn.style.backgroundColor = "#dc3545";
        removeBtn.style.color = "white";
        removeBtn.style.border = "none";
        removeBtn.style.borderRadius = "50%";
        removeBtn.style.width = "24px";
        removeBtn.style.height = "24px";
        removeBtn.style.cursor = "pointer";
        removeBtn.style.fontSize = "16px";
        removeBtn.style.lineHeight = "1";
        
        removeBtn.addEventListener('click', function() {
            selectedFiles.splice(i, 1);
            updatePreview();
            updateFileInput();
        });
        
        div.appendChild(fileInfo);
        div.appendChild(removeBtn);
        preview.appendChild(div);
    });
}

function updateFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    galleryInput.files = dt.files;
}
</script>



<?php
include '../includes/footer.php';
?>

