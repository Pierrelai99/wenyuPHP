<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

$newProductId = generateNextId($pdo, 'products', 'product_id', 'P');
$newSku = generateNextId($pdo, 'products', 'sku', 'SKU-');

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
    $targetAbs = $uploadDirAbs . $filename; // <- trailing slash handled by caller
    if (move_uploaded_file($file['tmp_name'], $targetAbs)) {
        return $uploadDirWeb . $filename; // <- trailing slash handled by caller
    }

    return null;
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $newProductId;
    $sku        = $newSku;
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $sale_price = ($_POST['sale_price'] === '' ? null : (float)$_POST['sale_price']);
    $status = ($_POST['status'] ?? 'inactive') === 'active' ? 'active' : 'inactive';
    $product_features = trim($_POST['product_features'] ?? '');
    $product_specifications = trim($_POST['product_specifications'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $stock_quantity = (int)($_POST['stock_quantity'] ?? 0);

    if ($sale_price === null) $sale_price = $price;

    // Handle image
   $uploadDirAbs = rtrim(__DIR__ . '/../assets/images/products/', '/\\') . DIRECTORY_SEPARATOR;
   $uploadDirWeb = '/assets/images/products/';

    // MAIN IMAGE
    $main_image_path = null;
    if (!empty($_FILES['main_image']['name'])) {
        $main_image_path = handleFileUpload(
            $_FILES['main_image'],
            $uploadDirAbs,
            $uploadDirWeb
        );
    }

    if (!$main_image_path) {
        // fallback if none uploaded
        $main_image_path = 'assets/images/no-image.png';
    }

    // GALLERY IMAGES (collect successfully uploaded paths)
    $gallery_paths = [];
    if (!empty($_FILES['gallery_images']['name'][0])) {
        foreach ($_FILES['gallery_images']['name'] as $i => $n) {
            $file = [
                'name'     => $_FILES['gallery_images']['name'][$i],
                'type'     => $_FILES['gallery_images']['type'][$i],
                'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                'error'    => $_FILES['gallery_images']['error'][$i],
                'size'     => $_FILES['gallery_images']['size'][$i],
            ];
            $gp = handleFileUpload($file, $uploadDirAbs, $uploadDirWeb);
            if ($gp){
            // Remove leading slash for consistency
            $gp = ltrim($gp, '/');  
            $gallery_paths[] = $gp;
        }
        }
    }
    


    try {
        $pdo->beginTransaction();

        // Insert product (store main image path to products.image for list/thumbnail usage)
        $stmt = $pdo->prepare("
    INSERT INTO products
        (product_id, sku, name, description, price, sale_price, stock_quantity, status, product_features, product_specifications, category_id, image)
    VALUES
        (:product_id, :sku, :name, :description, :price, :sale_price, :stock_quantity, :status, :product_features, :product_specifications, :category_id, :image)
    ");
    $stmt->execute([
    ':product_id'             => $product_id,
    ':sku'                    => $sku,
    ':name'                   => $name,
    ':description'            => $description,
    ':price'                  => $price,
    ':sale_price'             => $sale_price,
    ':stock_quantity'         => (int)($_POST['stock_quantity'] ?? 0),
    ':status'                 => $status,
    ':product_features'       => $product_features,
    ':product_specifications' => $product_specifications,
    ':category_id'            => $category_id,
    ':image'                  => ltrim($main_image_path ?? 'assets/images/no-image.png', '/')
    ]);


    // Insert GALLERY images ONLY into product_images (do NOT insert main image here)
        if (!empty($gallery_paths)) {
            $pi = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (:product_id, :image_path)");
            foreach ($gallery_paths as $gp) {
                $pi->execute([
                ':product_id' => $product_id,
                ':image_path' => $gp
            ]);
            }
        }

        $pdo->commit();

        $_SESSION['success'] = "Product added successfully!";
        header("Location: products.php");
        exit();

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $_SESSION['error'] = "Error adding product: " . $e->getMessage();
    }
}  

$categories = $pdo->query("SELECT * FROM categories WHERE parent_id IS NOT NULL ORDER BY name")->fetchAll();
include '../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <h1>Add New Product</h1>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="product_add.php" method="POST" enctype="multipart/form-data">
            <div class="grid-2">
                <div>
                    <div class="form-group">
                        <label for="product_id">Product ID</label>
                        <input type="text" id="product_id" name="product_id" value="<?php echo $newProductId; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                         <label for="description">Short Description</label>
                         <textarea id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Price (RM) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="sale_price">Sale Price (RM)</label>
                        <input type="number" id="sale_price" name="sale_price" step="0.01" min="0">
                        <small>Leave blank to default to Price.</small>
                    </div>

                    <div class="form-group">
                        <label for="sku">SKU</label>
                        <input type="text" id="sku" name="sku" value="<?php echo $newSku; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity *</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="0" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?php echo htmlspecialchars($c['category_id']); ?>">
                                    <?php echo htmlspecialchars($c['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product_features">Features</label>
                        <textarea name="product_features" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="product_specifications">Specifications</label>
                        <textarea name="product_specifications" rows="4"></textarea>
                    </div>


                </div>

                <div>
                    <div class="form-group">
                        <label for="main_image">Main Image</label>
                        <input type="file" id="main_image" name="main_image" accept="image/*">
                        <small>JPG, PNG, WEBP, GIF. Max ~5–10MB (server limit dependent).</small>
                    </div>

                    <div class="form-group">
                         <label for="gallery_images">Gallery Images (you can select multiple)</label>
                        <input type="file" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                        <div id="preview" style="margin-top:10px;"></div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Product</button>
                <a href="products.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<script>
const galleryInput = document.getElementById('gallery_images');
const preview = document.getElementById('preview');
let selectedFiles = []; // Array to store accumulated files

galleryInput.addEventListener('change', function(e) {
    // Add new files to our array (avoiding duplicates by name)
    Array.from(e.target.files).forEach(file => {
        // Check if file with same name already exists
        const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
        if (!exists) {
            selectedFiles.push(file);
        }
    });
    
    // Update the preview
    updatePreview();
    
    // Create a new FileList from our selected files and assign it back to the input
    updateFileInput();
});

function updatePreview() {
    preview.innerHTML = "";
    selectedFiles.forEach((file, i) => {
        const div = document.createElement("div");
        div.style.marginBottom = "5px";
        div.style.display = "flex";
        div.style.alignItems = "center";
        div.style.gap = "10px";
        
        const fileInfo = document.createElement("span");
        fileInfo.textContent = `${i+1}. ${file.name}`;
        
        const removeBtn = document.createElement("button");
        removeBtn.type = "button";
        removeBtn.textContent = "Remove";
        removeBtn.style.padding = "2px 8px";
        removeBtn.style.fontSize = "12px";
        removeBtn.style.backgroundColor = "#dc3545";
        removeBtn.style.color = "white";
        removeBtn.style.border = "none";
        removeBtn.style.borderRadius = "3px";
        removeBtn.style.cursor = "pointer";
        
        removeBtn.addEventListener('click', function() {
            selectedFiles.splice(i, 1);
            updatePreview();
            updateFileInput();
        });
        
        div.appendChild(fileInfo);
        div.appendChild(removeBtn);
        preview.appendChild(div);
    });
    
    if (selectedFiles.length === 0) {
        preview.innerHTML = "<em>No files selected</em>";
    }
}

function updateFileInput() {
    // Create a new DataTransfer object to build a new FileList
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    galleryInput.files = dt.files;
}

// Add a "Clear All" button
function addClearAllButton() {
    const clearAllBtn = document.createElement("button");
    clearAllBtn.type = "button";
    clearAllBtn.textContent = "Clear All Files";
    clearAllBtn.style.marginTop = "10px";
    clearAllBtn.style.padding = "5px 10px";
    clearAllBtn.style.backgroundColor = "#6c757d";
    clearAllBtn.style.color = "white";
    clearAllBtn.style.border = "none";
    clearAllBtn.style.borderRadius = "4px";
    clearAllBtn.style.cursor = "pointer";
    
    clearAllBtn.addEventListener('click', function() {
        selectedFiles = [];
        updatePreview();
        updateFileInput();
    });
    
    // Insert after the preview div
    preview.parentNode.insertBefore(clearAllBtn, preview.nextSibling);
}

// Initialize
updatePreview();
addClearAllButton();
</script>