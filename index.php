<?php
session_start();
require_once __DIR__ . "/includes/db.php";

// Get featured products
$stmt = $pdo->query("SELECT * FROM seafood_products WHERE featured = 1 ");
$featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page variables
$page_title = "Home";
$page_description = "Discover amazing toys and games for all ages at ToyLand Store. Free shipping on orders over $50!";
$show_breadcrumb = false;

// Include header
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background-overlay"></div>
    <div class="container">
        <div class="hero-layout">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-anchor"></i>
                    <span>Fresh From The Ocean</span>
                </div>
                <h1>Welcome to FishyWishy Seafood Store</h1>
                <p>Discover the freshest seafood from ocean to home in hours. Premium quality guaranteed with every catch.</p>

                <div class="hero-stats">
                    <div class="stat-item"><i class="fas fa-fish"></i><div><strong>50+</strong><span>Fresh Varieties</span></div></div>
                    <div class="stat-item"><i class="fas fa-shipping-fast"></i><div><strong>2 Hours</strong><span>Fast Delivery</span></div></div>
                    <div class="stat-item"><i class="fas fa-certificate"></i><div><strong>100%</strong><span>Quality Fresh</span></div></div>
                </div>

                <div class="hero-buttons">
                    <a href="public/products.php" class="btn btn-primary"><span>Shop Now</span><i class="fas fa-arrow-right"></i></a>
                    <a href="public/sale.php" class="btn btn-secondary"><i class="fas fa-tags"></i><span>View Sale</span></a>
                </div>
            </div>

            <div class="hero-visual">
                <div class="visual-center">
                    <div class="seafood-icon main-fish">🐟</div>
                    <div class="seafood-icon shrimp">🦐</div>
                    <div class="seafood-icon crab">🦀</div>
                    <div class="seafood-icon lobster">🦞</div>
                    <div class="seafood-icon squid">🦑</div>
                    <div class="seafood-icon octopus">🐙</div>
                </div>
                <div class="floating-bubbles">
                    <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>
                    <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>
                </div>
                <div class="wave-decoration wave-1"></div>
                <div class="wave-decoration wave-2"></div>
                <div class="wave-decoration wave-3"></div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item"><div class="feature-icon"><i class="fas fa-fish"></i></div><div class="feature-content"><h3>Fresh Daily Catch</h3><p>Seafood delivered fresh every morning</p></div></div>
            <div class="feature-item"><div class="feature-icon"><i class="fas fa-snowflake"></i></div><div class="feature-content"><h3>Cold Chain Guaranteed</h3><p>Temperature controlled from sea to home</p></div></div>
            <div class="feature-item"><div class="feature-icon"><i class="fas fa-certificate"></i></div><div class="feature-content"><h3>Quality Certified</h3><p>All seafood meets health & safety standards</p></div></div>
            <div class="feature-item"><div class="feature-icon"><i class="fas fa-shipping-fast"></i></div><div class="feature-content"><h3>Fast Delivery</h3><p>Same-day delivery available in selected areas</p></div></div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">

        <div class="section-header">
            <div class="section-badge"><i class="fas fa-fish"></i><span>Our Selection</span></div>
            <h2>Shop by Category</h2>
            <p>Explore our premium selection of fresh seafood</p>
        </div>

        <div class="categories-grid">

        <?php
        $stmt = $pdo->query("
            SELECT * 
            FROM seafood_categories 
            WHERE parent_id IS NULL 
              AND status = 'active'
        ");
        $parent_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($parent_categories as $cat):

            // Multiple fallback images
            $default_images = [
                "assets/images/categories/seafood_fish.png",
                "assets/images/categories/seafood_crustacean.png",
                "assets/images/categories/seafood_shellfish.png",
                "assets/images/categories/seafood_cephalopods.png",
                "assets/images/categories/seafood_premium_seafood.png"
            ];

            // Select image(s)
            $images = !empty($cat['category_image'])
                ? [str_replace("root/", "", $cat['category_image'])]
                : $default_images;
        ?>

            <div class="category-card">
                <div class="category-image">
                    <?php foreach ($images as $img): ?>
                        <img src="<?= $img ?>" class="category-img">
                    <?php endforeach; ?>
                </div>

                <div class="category-content">
                    <h3><?= htmlspecialchars($cat['category_name']) ?></h3>
                    <p><?= htmlspecialchars($cat['description'] ?? '') ?></p>

                    <a href="public/subcategories.php?parent_id=<?= urlencode($cat['category_id']) ?>" class="btn btn-outline">
                        <span>Shop Now</span>
                    </a>
                </div>
            </div>

        <?php endforeach; ?>

        </div> <!-- END categories-grid -->
    </div>
</section>

<!-- Featured Products Section -->
<?php
$featured_products = [
    ["product_id" => 1, "name" => "Norwegian Salmon Fillet", "sale_price" => 25.90, "image" => "assets/images/products/salmonfillet.png"],
    ["product_id" => 2, "name" => "Red Snapper", "sale_price" => 32.50, "image" => "assets/images/products/redsnapperslide.jpg"],
    ["product_id" => 3, "name" => "Threadfin", "sale_price" => 28.00, "image" => "assets/images/products/Threadfin.png"],
    ["product_id" => 4, "name" => "White Pomfret", "sale_price" => 35.00, "image" => "assets/images/products/whitePomfret.png"]
];
?>

<section class="featured-products-section">
    <div class="container">

        <div class="section-header">
            <h2>Featured Seafood</h2>
            <p>Fresh catches of the day</p>
        </div>

        <div class="products-grid">
            <?php foreach ($featured_products as $product): ?>
                <?php
                $image_path = $product['image'] ?: "assets/images/default-product.jpg";
                $product_name = $product['name'];
                $product_price = $product['sale_price'];
                $product_id = $product['product_id'];
                $product_url = "products.php?id=" . urlencode($product_id);
                ?>

                <div class="product-card">
                    <a href="<?= $product_url ?>">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($product_name) ?>">
                        </div>
                    </a>

                    <h3><a href="<?= $product_url ?>"><?= htmlspecialchars($product_name) ?></a></h3>
                    <p>RM<?= number_format($product_price, 2) ?></p>

                    <a class="btn btn-primary add-to-cart" data-product-id="<?= $product_id ?>">Add to Cart</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="view-all-products">
            <a href="public/products.php" class="btn btn-primary">View All Products</a>
        </div>

    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter for exclusive offers and new product announcements</p>
            <form class="newsletter-form" action="newsletter.php" method="POST">
                <input type="email" name="email" placeholder="Enter your email address" required>
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
