<?php
session_start();

// DB connection Af@12345678
//$pdo = new PDO("mysql:host=localhost;dbname=dbassignment;charset=utf8mb4", "root", "Af@12345678");
$pdo = new PDO("mysql:host=localhost;dbname=dbassignment;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    <div class="hero-slider">
        <div class="hero-slide active">
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
                            <div class="stat-item">
                                <i class="fas fa-fish"></i>
                                <div>
                                    <strong>50+</strong>
                                    <span>Fresh Varieties</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-shipping-fast"></i>
                                <div>
                                    <strong>2 Hours</strong>
                                    <span>Fast Delivery</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-certificate"></i>
                                <div>
                                    <strong>100%</strong>
                                    <span>Quality Fresh</span>
                                </div>
                            </div>
                        </div>
                        <div class="hero-buttons">
                            <a href="public/products.php" class="btn btn-primary">
                                <span>Shop Now</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="public/sale.php" class="btn btn-secondary">
                                <i class="fas fa-tags"></i>
                                <span>View Sale</span>
                            </a>
                        </div>
                    </div>
                    <div class="hero-image">
                        <div class="hero-seafood-collection">
                            <img src="assets/images/hero_bg.png" alt="Fresh Seafood Collection" class="floating-seafood">
                        </div>
                        <div class="hero-decorations">
                            <div class="decoration-wave wave-1"></div>
                            <div class="decoration-wave wave-2"></div>
                            <div class="decoration-wave wave-3"></div>
                            <div class="decoration-bubble bubble-1">🫧</div>
                            <div class="decoration-bubble bubble-2">🫧</div>
                            <div class="decoration-bubble bubble-3">🫧</div>
                            <div class="decoration-fish fish-1">🐟</div>
                            <div class="decoration-fish fish-2">🦐</div>
                            <div class="decoration-fish fish-3">🦀</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-fish"></i>
                </div>
                <div class="feature-content">
                    <h3>Fresh Daily Catch</h3>
                    <p>Seafood delivered fresh every morning</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-snowflake"></i>
                </div>
                <div class="feature-content">
                    <h3>Cold Chain Guaranteed</h3>
                    <p>Temperature controlled from sea to home</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="feature-content">
                    <h3>Quality Certified</h3>
                    <p>All seafood meets health & safety standards</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="feature-content">
                    <h3>Fast Delivery</h3>
                    <p>Same-day delivery available in selected areas</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Featured Banner - Seafood -->
<section class="banner-section">
    <div class="container">
        <div class="banner-container floating">
            <div class="banner-image">
                <img src="assets/images/banners/seafood_banner.png" alt="Fresh Seafood Collection" loading="lazy">
                <div class="banner-overlay"></div>
                <div class="banner-wave"></div>
            </div>
            <div class="banner-content">
                <div class="banner-badge">
                    <i class="fas fa-fish"></i>
                    <span>Fresh Daily</span>
                </div>
                <h3>Ocean Fresh Seafood Delivered</h3>
                <p>Premium quality seafood from trusted fishermen, delivered straight to your doorstep</p>
                <!-- TODO: Change link to redirect to Seafood category when categories are set up -->
                <a href="public/products.php" class="banner-btn">
                    <span>Browse Fresh Catch</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-fish"></i>
                <span>Our Selection</span>
            </div>
            <h2>Shop by Category</h2>
            <p>Explore our premium selection of fresh seafood, delivered daily from the ocean</p>
        </div>
        
        <div class="categories-grid">
            <?php
            // Fetch parent categories from DB
            $stmt = $pdo->query("SELECT * FROM seafood_categories WHERE parent_id IS NULL AND status = 'active'");
            $parent_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($parent_categories as $cat): 
                // Remove "root/" if needed
                $image_path = str_replace("root/", "", $cat['image']);
            ?>
                <div class="category-card">
                    <div class="category-image">
                        <?php if (!empty($cat['image'])): ?>
                            <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
                        <?php endif; ?>
                        <div class="category-overlay"></div>
                        <div class="category-badge">
                            <i class="fas fa-check-circle"></i>
                            <span>Fresh</span>
                        </div>
                    </div>
                    <div class="category-content">
                        <h3><?= htmlspecialchars($cat['name']) ?></h3>
                        <p><?= htmlspecialchars($cat['description']) ?></p>
                        <a href="public/subcategories.php?parent_id=<?= urlencode($cat['category_id']) ?>" class="btn btn-outline">
                            <span>Shop Now</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
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
            <!-- Large Premium Seafood Banner -->
            <div class="large-banner">
                <div class="banner-container banner-center">
                    <div class="banner-image">
                        <img src="assets/images/banners/premium_seafood_banner.png" alt="Premium Seafood Collection" loading="lazy">
                        <div class="banner-overlay"></div>
                        <div class="banner-wave"></div>
                    </div>
                    <div class="banner-content">
                        <div class="banner-badge">
                            <i class="fas fa-star"></i>
                            <span>Premium Selection</span>
                        </div>
                        <h3>Discover Our Premium Seafood</h3>
                        <p>Wild-caught, sustainably sourced, and delivered fresh to your table</p>
                        <!-- TODO: Change link to redirect to Premium Seafood category when categories are set up -->
                        <a href="public/products.php" class="banner-btn">
                            <span>Shop Premium Selection</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Small Fresh Fish Banner -->
            <div class="small-banner">
                <div class="banner-container banner-reverse">
                    <div class="banner-image">
                        <img src="assets/images/banners/fresh_fish_banner.png" alt="Fresh Fish Collection" loading="lazy">
                        <div class="banner-overlay"></div>
                    </div>
                    <div class="banner-content">
                        <div class="banner-icon">
                            <i class="fas fa-fish"></i>
                        </div>
                        <h3>Fresh Fish Daily</h3>
                        <p>Caught this morning, on your plate tonight</p>
                        <!-- TODO: Change link to redirect to Fish category when categories are set up -->
                        <a href="public/products.php" class="banner-btn">
                            <span>Browse Fish</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Small Shellfish Banner -->
            <div class="small-banner">
                <div class="banner-container">
                    <div class="banner-image">
                        <img src="assets/images/banners/shellfish_banner.png" alt="Shellfish Collection" loading="lazy">
                        <div class="banner-overlay"></div>
                    </div>
                    <div class="banner-content">
                        <div class="banner-icon">
                            <i class="fas fa-shrimp"></i>
                        </div>
                        <h3>Premium Shellfish</h3>
                        <p>Lobsters, crabs, prawns & more delicacies</p>
                        <!-- TODO: Change link to redirect to Shellfish category when categories are set up -->
                        <a href="public/products.php" class="banner-btn">
                            <span>Explore Now</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
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
            <div class="section-badge">
                <i class="fas fa-star"></i>
                <span>Freshest Picks</span>
            </div>
            <h2>Featured Seafood</h2>
            <p>Our most popular and freshest catches of the day</p>
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
                            <button class="quick-view" data-product-id="<?= $product['product_id'] ?>">
                                <i class="fas fa-eye"></i>
                                <span>Quick View</span>
                            </button>
                            <button class="add-to-wishlist" data-product-id="<?= $product['product_id'] ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <?php if ($product['sale_price'] < $product['price']): ?>
                            <div class="product-badge sale">
                                <i class="fas fa-tag"></i>
                                <span>Sale</span>
                            </div>
                        <?php endif; ?>
                        <div class="product-badge fresh">
                            <i class="fas fa-fish"></i>
                            <span>Fresh</span>
                        </div>
                    </div>
                    <div class="product-content">
                        <h3><a href="<?= $product_url ?>"><?= htmlspecialchars($product['name']) ?></a></h3>
                        <div class="product-meta">
                            <span class="product-origin">
                                <i class="fas fa-anchor"></i>
                                Wild Caught
                            </span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">RM<?= number_format($product['sale_price'], 2) ?></span>
                            <?php if ($product['sale_price'] < $product['price']): ?>
                                <span class="original-price">RM<?= number_format($product['price'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <button class="add-to-cart" data-product-id="<?= $product['product_id'] ?>">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Add to Cart</span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="view-all-products">
            <a href="public/products.php" class="btn btn-primary">
                <span>View All Products</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Fresh Catch Speed Banner -->
<section class="banner-section">
    <div class="container">
        <div class="banner-container banner-reverse floating">
            <div class="banner-image-left">
                <img src="assets/images/banners/fresh_catch_banner.png" alt="Fresh Daily Catch Collection" loading="lazy">
                <div class="banner-overlay"></div>
                <div class="banner-bubbles">
                    <div class="bubble bubble-1">🫧</div>
                    <div class="bubble bubble-2">🫧</div>
                    <div class="bubble bubble-3">🫧</div>
                </div>
            </div>
            <div class="banner-content">
                <div class="banner-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Freshness Delivered in Hours</h3>
                <p>Experience the fastest delivery of ocean-fresh seafood straight to your kitchen</p>
                <div class="banner-features">
                    <div class="feature-badge">
                        <i class="fas fa-shipping-fast"></i>
                        <span>2-Hour Delivery</span>
                    </div>
                    <div class="feature-badge">
                        <i class="fas fa-snowflake"></i>
                        <span>Cold Chain</span>
                    </div>
                    <div class="feature-badge">
                        <i class="fas fa-certificate"></i>
                        <span>Quality Assured</span>
                    </div>
                </div>
                <!-- TODO: Change link to redirect to Fresh Catch category when categories are set up -->
                <a href="public/products.php" class="banner-btn">
                    <span>Order Fresh Now</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
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
                    <div class="promo-badge">
                        <i class="fas fa-sparkles"></i>
                        <span>Just Arrived</span>
                    </div>
                    <h2>Fresh Daily Catches</h2>
                    <p>Explore today's fresh arrivals straight from the ocean to your table</p>
                    <a href="new-arrivals.php" class="btn btn-white">
                        <span>Browse Fresh</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="promo-image">
                    <img src="assets/images/promo-fresh-arrivals.jpg" alt="Fresh Daily Catches">
                    <div class="promo-overlay"></div>
                    <div class="promo-decoration">
                        <div class="decoration-fish">🐟</div>
                        <div class="decoration-bubble">🫧</div>
                    </div>
                </div>
            </div>
            
            <div class="promo-card">
                <div class="promo-content">
                    <div class="promo-badge sale-badge">
                        <i class="fas fa-tag"></i>
                        <span>Special Offer</span>
                    </div>
                    <h2>Weekly Specials</h2>
                    <p>Save up to 40% on premium seafood selections this week only</p>
                    <a href="sale.php" class="btn btn-white">
                        <span>View Deals</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="promo-image">
                    <img src="assets/images/promo-seafood-sale.jpg" alt="Weekly Specials">
                    <div class="promo-overlay"></div>
                    <div class="promo-decoration">
                        <div class="decoration-fish">🦐</div>
                        <div class="decoration-bubble">🫧</div>
                    </div>
                    <div class="sale-tag">
                        <span class="sale-percentage">40%</span>
                        <span class="sale-text">OFF</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-comments"></i>
                <span>Customer Reviews</span>
            </div>
            <h2>What Our Customers Say</h2>
            <p>Real reviews from satisfied seafood lovers</p>
        </div>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <div class="testimonial-content">
                    <i class="fas fa-quote-left quote-icon"></i>
                    <p>"The freshest seafood I've ever had! Delivered within 2 hours and the quality was exceptional. The salmon was restaurant-grade!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <img src="assets/images/customer-1.jpg" alt="Sarah Lim">
                    </div>
                    <div class="author-info">
                        <h4>Sarah Lim</h4>
                        <span><i class="fas fa-check-circle"></i> Verified Customer</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <div class="testimonial-content">
                    <i class="fas fa-quote-left quote-icon"></i>
                    <p>"Amazing service! Cold chain maintained perfectly, and the prawns were still alive when delivered. This is now my go-to seafood supplier!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <img src="assets/images/customer-2.jpg" alt="Ahmad Rahman">
                    </div>
                    <div class="author-info">
                        <h4>Ahmad Rahman</h4>
                        <span><i class="fas fa-check-circle"></i> Verified Customer</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <div class="testimonial-content">
                    <i class="fas fa-quote-left quote-icon"></i>
                    <p>"Premium quality at reasonable prices. The lobsters were huge and fresh. Great for special occasions. Highly recommended!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <img src="assets/images/customer-3.jpg" alt="Michelle Tan">
                    </div>
                    <div class="author-info">
                        <h4>Michelle Tan</h4>
                        <span><i class="fas fa-check-circle"></i> Verified Customer</span>
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
