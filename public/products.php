<?php
session_start();
require_once '../includes/db.php';

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=web_db;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get products by category_id if provided
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND status = 'active'");
    $stmt->execute([$category_id]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Default: get all products
    $stmt = $pdo->query("SELECT * FROM products WHERE status = 'active'");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Page variables
$page_title = "Products";
$page_description = "Browse our complete collection of toys and games for all ages";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'products.php', 'title' => 'Products']
];

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';
$age_group = isset($_GET['age_group']) ? $_GET['age_group'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12;

// Include header
include '../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(to right, #fddde6, #fceabb); padding: 3em 0; border-radius: 0 0 50px 50px;">
    <div class="container">
        <h1 style="color: #333;">All Products</h1>
        <p style="color: #333;">Discover amazing toys and games for every age and interest</p>
    </div>
</section>

<!-- Products Section -->
<section class="products-section">
    <div class="container">
        <div class="products-layout">
             <main class="products-content">
                <div class="products-header">
                    <h2>All Products</h2>
                </div>

                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <?php
                            $image_path = str_replace("root/", "", $product['image']);
                            $product_url = "product.php?id=" . urlencode($product['product_id']);
                        ?>
                        <div class="product-card">
                            <div class="product-image">
                                <a href="<?= $product_url ?>">
                                    <img src="/<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                </a>

                               <div class="product-overlay">
                                 <button class="quick-view" data-product-id="<?= $product['product_id'] ?>">Quick View</button>
                                 <button class="add-to-wishlist" data-product-id="<?= $product['product_id'] ?>"><i class="far fa-heart"></i></button>
                                </div>
                               <?php if ($product['sale_price'] < $product['price']): ?>
                                  <div class="product-badge sale">Sale</div>
                              <?php endif; ?>
                           </div>

                        <div class="product-content">
                             <h3><a href="<?= $product_url ?>"><?= htmlspecialchars($product['name']) ?></a></h3>
                             <div class="product-price">
                                  <span class="current-price">RM<?= number_format($product['sale_price'], 2) ?></span>
                                  <?php if ($product['sale_price'] < $product['price']): ?>
                                    <span class="original-price">RM<?= number_format($product['price'], 2) ?></span>
                                  <?php endif; ?>
                            </div>
                            <button class="add-to-cart" data-product-id="<?= $product['product_id'] ?>">Add to Cart</button>
                        </div>
                </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>


            <!-- Sidebar Filters -->
            <aside class="products-sidebar">
                <div class="filter-section">
                    <h3>Filters</h3>
                    
                    <!-- Category Filter -->
                    <div class="filter-group">
                        <h4>Category</h4>
                        <ul class="filter-list">
                            <li>
                                <label>
                                    <input type="checkbox" name="category" value="action-figures" <?php echo ($category == 'action-figures') ? 'checked' : ''; ?>>
                                    Action Figures
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="category" value="board-games" <?php echo ($category == 'board-games') ? 'checked' : ''; ?>>
                                    Board Games
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="category" value="educational" <?php echo ($category == 'educational') ? 'checked' : ''; ?>>
                                    Educational Toys
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="category" value="outdoor" <?php echo ($category == 'outdoor') ? 'checked' : ''; ?>>
                                    Outdoor Toys
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="category" value="arts-crafts" <?php echo ($category == 'arts-crafts') ? 'checked' : ''; ?>>
                                    Arts & Crafts
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="category" value="babies-toddlers" <?php echo ($category == 'babies-toddlers') ? 'checked' : ''; ?>>
                                    Babies & Toddlers
                                </label>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Price Filter -->
                    <div class="filter-group">
                        <h4>Price Range</h4>
                        <div class="price-range">
                            <input type="number" name="price_min" placeholder="Min" value="<?php echo $price_min; ?>">
                            <span>to</span>
                            <input type="number" name="price_max" placeholder="Max" value="<?php echo $price_max; ?>">
                        </div>
                    </div>
                    
                    <!-- Age Group Filter -->
                    <div class="filter-group">
                        <h4>Age Group</h4>
                        <ul class="filter-list">
                            <li>
                                <label>
                                    <input type="checkbox" name="age_group" value="0-2" <?php echo ($age_group == '0-2') ? 'checked' : ''; ?>>
                                    0-2 years
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="age_group" value="3-5" <?php echo ($age_group == '3-5') ? 'checked' : ''; ?>>
                                    3-5 years
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="age_group" value="6-8" <?php echo ($age_group == '6-8') ? 'checked' : ''; ?>>
                                    6-8 years
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="age_group" value="9-12" <?php echo ($age_group == '9-12') ? 'checked' : ''; ?>>
                                    9-12 years
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="age_group" value="13+" <?php echo ($age_group == '13+') ? 'checked' : ''; ?>>
                                    13+ years
                                </label>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Brand Filter -->
                    <div class="filter-group">
                        <h4>Brand</h4>
                        <ul class="filter-list">
                            <li>
                                <label>
                                    <input type="checkbox" name="brand" value="lego" <?php echo ($brand == 'lego') ? 'checked' : ''; ?>>
                                    LEGO
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="brand" value="hasbro" <?php echo ($brand == 'hasbro') ? 'checked' : ''; ?>>
                                    Hasbro
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="brand" value="mattel" <?php echo ($brand == 'mattel') ? 'checked' : ''; ?>>
                                    Mattel
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="brand" value="fisher-price" <?php echo ($brand == 'fisher-price') ? 'checked' : ''; ?>>
                                    Fisher-Price
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="brand" value="melissa-doug" <?php echo ($brand == 'melissa-doug') ? 'checked' : ''; ?>>
                                    Melissa & Doug
                                </label>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Clear Filters -->
                    <button class="btn btn-outline clear-filters">Clear All Filters</button>
                </div>
            </aside>
            
                
                <!-- Pagination -->
                <div class="pagination">
                    <ul class="pagination-list">
                        <li class="pagination-item">
                            <a href="?page=1" class="pagination-link">First</a>
                        </li>
                        <li class="pagination-item">
                            <a href="?page=<?php echo $page - 1; ?>" class="pagination-link <?php echo ($page <= 1) ? 'disabled' : ''; ?>">Previous</a>
                        </li>
                        <li class="pagination-item">
                            <a href="?page=1" class="pagination-link <?php echo ($page == 1) ? 'active' : ''; ?>">1</a>
                        </li>
                        <li class="pagination-item">
                            <a href="?page=2" class="pagination-link <?php echo ($page == 2) ? 'active' : ''; ?>">2</a>
                        </li>
                        <li class="pagination-item">
                            <a href="?page=3" class="pagination-link <?php echo ($page == 3) ? 'active' : ''; ?>">3</a>
                        </li>
                        <li class="pagination-item">
                            <a href="?page=4" class="pagination-link <?php echo ($page == 4) ? 'active' : ''; ?>">4</a>
                        </li>
                        <li class="pagination-item">
                            <a href="?page=<?php echo $page + 1; ?>" class="pagination-link <?php echo ($page >= 4) ? 'disabled' : ''; ?>">Next</a>
                        </li>
                        <li class="pagination-item">
                            <a href="?page=4" class="pagination-link">Last</a>
                        </li>
                    </ul>
                </div>
            </main>
        </div>
    </div>
</section>

<!-- Quick View Modal -->
<div id="quick-view-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="quick-view-content">
            <!-- Quick view content will be loaded here -->
        </div>
    </div>
</div>

<?php
// Include footer
include '../includes/footer.php';
?> 