<?php
session_start();

// DB connection Af@12345678
$pdo = new PDO("mysql:host=localhost;dbname=dbassignment;charset=utf8mb4", "root", "Af@12345678");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get featured products
$stmt = $pdo->query("SELECT * FROM products WHERE featured = 1 ");
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
    <div class="hero-slider">
        <div class="hero-slide active">
            <div class="container">
                <div class="hero-layout">
                    <div class="hero-content">
                        <h1>Welcome to ToyLand Store</h1>
                        <p>Discover amazing toys and games that spark imagination and create lasting memories</p>
                        <div class="hero-buttons">
                            <a href="public/products.php" class="btn btn-primary">Shop Now</a>
                            <a href="public/sale.php" class="btn btn-secondary">View Sale</a>
                        </div>
                    </div>
                    <div class="hero-image">
                        <div class="hero-toy-collection">
                            <img src="assets/images/hero_bg.png" alt="Amazing Toy Collection" class="floating-toys">
                        </div>
                        <div class="hero-decorations">
                            <div class="decoration-circle circle-1"></div>
                            <div class="decoration-circle circle-2"></div>
                            <div class="decoration-circle circle-3"></div>
                            <div class="decoration-star star-1">⭐</div>
                            <div class="decoration-star star-2">✨</div>
                            <div class="decoration-star star-3">🌟</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <i class="fas fa-shipping-fast"></i>
                <h3>Free Shipping</h3>
                <p>Free shipping on orders over $50</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-shield-alt"></i>
                <h3>Safe & Secure</h3>
                <p>All toys meet safety standards</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-undo"></i>
                <h3>Easy Returns</h3>
                <p>30-day return policy</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-headset"></i>
                <h3>24/7 Support</h3>
                <p>Customer service always available</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Banner - LEGO -->
<section class="banner-section">
    <div class="container">
        <div class="banner-container floating">
            <div class="banner-image">
                <img src="assets/images/banners/lego_banner.png" alt="LEGO Collection" loading="lazy">
                <div class="banner-overlay"></div>
            </div>
            <div class="banner-content">
                <h3>Build Your Dreams with LEGO</h3>
                <p>Endless creativity and imagination await with our premium LEGO collection</p>
                <!-- TODO: Change link to redirect to LEGO category when categories are set up -->
                <a href="public/products.php" class="banner-btn">Explore LEGO Sets</a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Shop by Category</h2>
            <p>Find the perfect toy for every age and interest</p>
        </div>
        
        <div class="categories-grid">
            <?php
            // Fetch parent categories from DB
            $stmt = $pdo->query("SELECT * FROM categories WHERE parent_id IS NULL AND status = 'active'");
            $parent_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($parent_categories as $cat): 
                // Remove "root/" if needed (like you did for products)
                $image_path = str_replace("root/", "", $cat['image']);
            ?>
                <div class="category-card">
                    <div class="category-image">
                        <?php if (!empty($cat['image'])): ?>
                            <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="category-content">
                        <h3><?= htmlspecialchars($cat['name']) ?></h3>
                        <p><?= htmlspecialchars($cat['description']) ?></p>
                        <a href="public/subcategories.php?parent_id=<?= urlencode($cat['category_id']) ?>" class="btn btn-outline">Shop Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Banners Grid -->
<section class="banner-section">
    <div class="container">
        <div class="featured-banners">
            <!-- Large Barbie Banner -->
            <div class="large-banner">
                <div class="banner-container banner-center">
                    <div class="banner-image">
                        <img src="assets/images/banners/barbie_banner.png" alt="Barbie Collection" loading="lazy">
                        <div class="banner-overlay"></div>
                    </div>
                    <div class="banner-content">
                        <h3>Discover Barbie's Magical World</h3>
                        <p>Fashion, fun, and endless adventures with the world's most beloved doll</p>
                        <!-- TODO: Change link to redirect to Barbie category when categories are set up -->
                        <a href="public/products.php" class="banner-btn">Shop Barbie Collection</a>
                    </div>
                </div>
            </div>
            
            <!-- Small Hot Wheels Banners -->
            <div class="small-banner">
                <div class="banner-container banner-reverse">
                    <div class="banner-image">
                        <img src="assets/images/banners/hotwheels_banner.png" alt="Hot Wheels Collection" loading="lazy">
                        <div class="banner-overlay"></div>
                    </div>
                    <div class="banner-content">
                        <h3>Hot Wheels Racing</h3>
                        <p>Speed into action with premium die-cast cars</p>
                        <!-- TODO: Change link to redirect to Hot Wheels category when categories are set up -->
                        <a href="public/products.php" class="banner-btn">Race Now</a>
                    </div>
                </div>
            </div>
            
            <div class="small-banner">
                <div class="banner-container">
                    <div class="banner-image">
                        <img src="assets/images/banners/lego_banner.png" alt="LEGO Collection" loading="lazy">
                        <div class="banner-overlay"></div>
                    </div>
                    <div class="banner-content">
                        <h3>LEGO Adventures</h3>
                        <p>Build, create, and explore infinite possibilities</p>
                        <!-- TODO: Change link to redirect to LEGO category when categories are set up -->
                        <a href="public/products.php" class="banner-btn">Build Dreams</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="featured-products-section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Products</h2>
            <p>Our most popular and highly-rated toys</p>
        </div>
        
        <div class="products-grid">
            <?php foreach ($featured_products as $product): ?>
                <?php
                    $image_path = str_replace("root/", "", $product['image']);
                    $product_url = "public/product.php?id=" . urlencode($product['product_id']);
                ?>
                <div class="product-card">
                    <div class="product-image">
                        <a href="<?= $product_url ?>">
                            <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
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

        <div class="view-all-products">
            <a href="public/products.php" class="btn btn-primary">View All Products</a>
        </div>
    </div>
</section>

<!-- Hot Wheels Speed Banner -->
<section class="banner-section">
    <div class="container">
        <div class="banner-container banner-reverse floating">
            <div class="banner-image-left">
                <img src="assets/images/banners/hotwheels_banner.png" alt="Hot Wheels High-Speed Collection" loading="lazy">
                <div class="banner-overlay"></div>
            </div>
            <div class="banner-content">
                <h3>Feel the Need for Speed</h3>
                <p>Race into excitement with Hot Wheels' fastest and most thrilling car collection</p>
                <!-- TODO: Change link to redirect to Hot Wheels category when categories are set up -->
                <a href="public/products.php" class="banner-btn">Start Your Engines</a>
            </div>
        </div>
    </div>
</section>

<!-- Promotional Section -->
<section class="promotional-section">
    <div class="container">
        <div class="promo-grid">
            <div class="promo-card">
                <div class="promo-content">
                    <h2>New Arrivals</h2>
                    <p>Check out the latest toys and games that just arrived</p>
                    <a href="new-arrivals.php" class="btn btn-white">Shop New</a>
                </div>
                <div class="promo-image">
                    <img src="assets/images/promo-new-arrivals.jpg" alt="New Arrivals">
                </div>
            </div>
            
            <div class="promo-card">
                <div class="promo-content">
                    <h2>Sale Items</h2>
                    <p>Up to 50% off on selected toys and games</p>
                    <a href="sale.php" class="btn btn-white">Shop Sale</a>
                </div>
                <div class="promo-image">
                    <img src="assets/images/promo-sale.jpg" alt="Sale Items">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2>What Our Customers Say</h2>
            <p>Real reviews from happy families</p>
        </div>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Amazing selection of educational toys. My kids love learning through play!"</p>
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/customer-1.jpg" alt="Sarah Johnson">
                    <div class="author-info">
                        <h4>Sarah Johnson</h4>
                        <span>Verified Customer</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Fast shipping and excellent customer service. Highly recommended!"</p>
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/customer-2.jpg" alt="Mike Davis">
                    <div class="author-info">
                        <h4>Mike Davis</h4>
                        <span>Verified Customer</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Quality toys at great prices. Perfect for birthday gifts!"</p>
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/customer-3.jpg" alt="Lisa Chen">
                    <div class="author-info">
                        <h4>Lisa Chen</h4>
                        <span>Verified Customer</span>
                    </div>
                </div>
            </div>
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

<?php
// Include footer
include 'includes/footer.php';
?>
