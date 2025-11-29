  <?php
session_start();

// Get product ID
$product_id = isset($_GET['id']) ? $_GET['id'] : '';

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=web_db;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get product
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<h2>Product not found</h2>";
    exit;
}

// Main image comes from products table
$main_image = $product['image'];

// Gallery images come from product_images table
$image_stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
$image_stmt->execute([$product_id]);
$thumbnail_images = $image_stmt->fetchAll(PDO::FETCH_COLUMN);

// Page variables
$page_title = $product['name'];
$page_description = $product['description'];
$show_breadcrumb = true;
$breadcrumb_items = [['url' => 'products.php', 'title' => 'Products']];

// Include header
include '../includes/header.php';
?>

<!-- Product Details Section -->
<section class="product-details-section">
    <div class="container">
        <div class="product-details-layout">
            <!-- Product Images -->
            <div class="product-images">
                <div class="main-image">
                    <?php if (!empty($main_image)): ?>
                        <img src="/<?= htmlspecialchars($main_image) ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="main-product-image" onerror="this.src='https://via.placeholder.com/500x400/f93c64/ffffff?text=<?= urlencode($product['name']) ?>'">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/500x400/f93c64/ffffff?text=<?= urlencode($product['name']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="main-product-image">
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($thumbnail_images)): ?>
                <div class="thumbnail-images">
                    <div class="thumbnail active" data-image="/<?= htmlspecialchars($main_image) ?>">
                        <img src="/<?= htmlspecialchars($main_image) ?>" alt="Main view" onerror="this.src='https://via.placeholder.com/100x80/f93c64/ffffff?text=Main'">
                    </div>
                    <?php foreach ($thumbnail_images as $img): ?>
                        <div class="thumbnail" data-image="/<?= htmlspecialchars($img) ?>">
                           <img src="/<?= htmlspecialchars($img) ?>" alt="Alternative view" onerror="this.src='https://via.placeholder.com/100x80/f93c64/ffffff?text=Alt'">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="product-header">
                    <h1><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span>5.0 out of 5</span>
                        <span>(24 reviews)</span>
                    </div>
                    
                    <div class="product-badges">
                        <?php if ($product['price'] > $product['sale_price']): ?>
                            <span class="badge sale">Sale</span>
                        <?php endif; ?>
                        <span class="badge new">New</span>
                    </div>
                </div>
                
                <div class="product-price">
                    <span class="current-price">RM <?= number_format($product['sale_price'], 2) ?></span>
                    <?php if ($product['price'] > $product['sale_price']): ?>
                        <span class="original-price">RM <?= number_format($product['price'], 2) ?></span>
                        <span class="discount">Save RM <?= number_format($product['price'] - $product['sale_price'], 2) ?> (<?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>% off)</span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
                
                <div class="product-options">
                    <div class="option-group">
                        <label>Available Colors:</label>
                        <div class="color-options">
                            <button class="color-option active" data-color="blue" style="background-color: #0066cc;" title="Blue"></button>
                            <button class="color-option" data-color="red" style="background-color: #cc0000;" title="Red"></button>
                            <button class="color-option" data-color="green" style="background-color: #00cc00;" title="Green"></button>
                        </div>
                    </div>
                </div>
                
                <div class="product-actions">
                    <form method="post" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                </div>
                
                <div class="product-meta">
                    <div><strong>SKU:</strong> <?= htmlspecialchars($product['sku']) ?></div>
                    <div><strong>Stock:</strong> <?= htmlspecialchars($product['stock_quantity']) ?> items available</div>
                    <div><strong>Category:</strong> <?= htmlspecialchars($product['category_id']) ?></div>
                    <div><strong>Brand:</strong> Toy Land</div>
                </div>
                
                <div class="product-shipping">
                    <div class="shipping-info">
                        <i class="fas fa-shipping-fast"></i>
                        <div>
                            <strong>Free Shipping</strong>
                            <span>On orders over RM 150</span>
                        </div>
                    </div>
                    <div class="shipping-info">
                        <i class="fas fa-undo"></i>
                        <div>
                            <strong>30-Day Returns</strong>
                            <span>Easy returns & exchanges</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Tabs Section -->
<section class="product-tabs-section">
    <div class="container">
        <div class="product-tabs">
            <div class="tab-nav">
                <button class="tab-btn active" data-tab="description">Description</button>
                <button class="tab-btn" data-tab="specifications">Specifications</button>
                <button class="tab-btn" data-tab="reviews">Reviews (24)</button>
                <button class="tab-btn" data-tab="shipping">Shipping & Returns</button>
            </div>
            
            <div class="tab-content">
                <!-- Description Tab -->
                <div class="tab-pane active" id="description">
                    <div class="product-description-content">
                        <h3>Product Description</h3>
                        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                        
                        <h4>Key Features:</h4>
                        <ul>
                          <?php 
                          $features = explode("\n", $product['product_features']);
                          foreach ($features as $feature): 
                             $clean = trim($feature, "-• \t\r\n");
                             if ($clean): ?>
                             <li><?= htmlspecialchars($clean) ?></li>
                          <?php endif; endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Specifications Tab -->
                <div class="tab-pane" id="specifications">
                    <div class="product-specifications">
                        <br>
                         <table class="specs-table">
                         <?php
                         $lines = explode("\n", $product['product_specifications']);
                         foreach ($lines as $line) {
                              if (strpos($line, ':') !== false) {
                                 list($label, $value) = explode(':', $line, 2);
                                 echo '<tr>';
                                 echo '<td><strong>' . htmlspecialchars(trim($label)) . ':</strong></td>';
                                 echo '<td>' . htmlspecialchars(trim($value)) . '</td>';
                                 echo '</tr>';}       
                                }
                        ?>
                        </table>
                    </div>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-pane" id="reviews">
                    <div class="product-reviews">
                        <div class="reviews-header">
                            <h3>Customer Reviews</h3>
                            <div class="reviews-summary">
                                <div class="average-rating">
                                    <span class="rating-number">5.0</span>
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span>out of 5</span>
                                </div>
                                <div class="rating-breakdown">
                                    <div class="rating-bar">
                                        <span>5 stars</span>
                                        <div class="bar"><div class="fill" style="width: 85%;"></div></div>
                                        <span>20</span>
                                    </div>
                                    <div class="rating-bar">
                                        <span>4 stars</span>
                                        <div class="bar"><div class="fill" style="width: 10%;"></div></div>
                                        <span>2</span>
                                    </div>
                                    <div class="rating-bar">
                                        <span>3 stars</span>
                                        <div class="bar"><div class="fill" style="width: 5%;"></div></div>
                                        <span>1</span>
                                    </div>
                                    <div class="rating-bar">
                                        <span>2 stars</span>
                                        <div class="bar"><div class="fill" style="width: 0%;"></div></div>
                                        <span>0</span>
                                    </div>
                                    <div class="rating-bar">
                                        <span>1 star</span>
                                        <div class="bar"><div class="fill" style="width: 0%;"></div></div>
                                        <span>0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="reviews-list">
                            <!-- Review 1 -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <img src="assets/images/reviewer-1.jpg" alt="John D.">
                                        <div>
                                            <h4>John D.</h4>
                                            <div class="stars">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="review-date">March 15, 2024</span>
                                </div>
                                <div class="review-content">
                                    <h5>Excellent Quality!</h5>
                                    <p>This action figure exceeded my expectations. The articulation is smooth, the paint job is clean, and the accessories are well-made. My son loves playing with it and I enjoy displaying it on my shelf.</p>
                                </div>
                            </div>
                            
                            <!-- Review 2 -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <img src="assets/images/reviewer-2.jpg" alt="Sarah M.">
                                        <div>
                                            <h4>Sarah M.</h4>
                                            <div class="stars">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="review-date">March 10, 2024</span>
                                </div>
                                <div class="review-content">
                                    <h5>Perfect Gift</h5>
                                    <p>Bought this for my nephew's birthday and he absolutely loves it! The figure is sturdy and the joints hold poses well. Great value for the price.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="write-review-section">
                            <h4>Write a Review</h4>
                            <form class="review-form">
                                <div class="rating-input">
                                    <label>Your Rating:</label>
                                    <div class="star-rating">
                                        <input type="radio" name="rating" value="5" id="star5">
                                        <label for="star5"><i class="far fa-star"></i></label>
                                        <input type="radio" name="rating" value="4" id="star4">
                                        <label for="star4"><i class="far fa-star"></i></label>
                                        <input type="radio" name="rating" value="3" id="star3">
                                        <label for="star3"><i class="far fa-star"></i></label>
                                        <input type="radio" name="rating" value="2" id="star2">
                                        <label for="star2"><i class="far fa-star"></i></label>
                                        <input type="radio" name="rating" value="1" id="star1">
                                        <label for="star1"><i class="far fa-star"></i></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="review-title">Review Title:</label>
                                    <input type="text" id="review-title" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="review-content">Your Review:</label>
                                    <textarea id="review-content" name="content" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Tab -->
                <div class="tab-pane" id="shipping">
                    <div class="shipping-returns">
                        <h3>Shipping & Returns</h3>
                        
                        <div class="shipping-info">
                            <h4>Shipping Information</h4>
                            <ul>
                                <li><strong>Free Shipping:</strong> On orders over $50</li>
                                <li><strong>Standard Shipping:</strong> 3-5 business days ($5.99)</li>
                                <li><strong>Express Shipping:</strong> 1-2 business days ($12.99)</li>
                                <li><strong>Overnight Shipping:</strong> Next business day ($19.99)</li>
                            </ul>
                        </div>
                        
                        <div class="returns-info">
                            <h4>Returns & Exchanges</h4>
                            <ul>
                                <li><strong>Return Period:</strong> 30 days from delivery</li>
                                <li><strong>Return Shipping:</strong> Free for defective items</li>
                                <li><strong>Refund Method:</strong> Original payment method</li>
                                <li><strong>Condition:</strong> Must be unused and in original packaging</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products Section -->
<section class="related-products">
    <div class="container">
        <h3>You Might Also Like</h3>
        
        <div class="related-grid">
            <!-- Related Product 1 -->
            <div class="related-item">
                <img src="../assets/images/product-9.jpg" alt="Robot Companion Figure" onerror="this.src='https://via.placeholder.com/200x150/f93c64/ffffff?text=Toy'">
                <h4><a href="product.php?id=9">Robot Companion Figure</a></h4>
                <div class="price">$19.99</div>
            </div>
            
            <!-- Related Product 2 -->
            <div class="related-item">
                <img src="../assets/images/product-10.jpg" alt="Space Explorer Set" onerror="this.src='https://via.placeholder.com/200x150/f93c64/ffffff?text=Toy'">
                <h4><a href="product.php?id=10">Space Explorer Set</a></h4>
                <div class="price">$34.99</div>
            </div>
            
            <!-- Related Product 3 -->
            <div class="related-item">
                <img src="../assets/images/product-11.jpg" alt="Adventure Action Figure" onerror="this.src='https://via.placeholder.com/200x150/f93c64/ffffff?text=Toy'">
                <h4><a href="product.php?id=11">Adventure Action Figure</a></h4>
                <div class="price">$24.99</div>
            </div>
            
            <!-- Related Product 4 -->
            <div class="related-item">
                <img src="../assets/images/product-12.jpg" alt="Fantasy Warrior Set" onerror="this.src='https://via.placeholder.com/200x150/f93c64/ffffff?text=Toy'">
                <h4><a href="product.php?id=12">Fantasy Warrior Set</a></h4>
                <div class="price">$29.99</div>
            </div>
        </div>
    </div>
</section>
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/product-10.jpg" alt="Space Warrior Figure">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="10">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="10"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge sale">Sale</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=10">Space Warrior Figure</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>(21 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">$22.99</span>
                        <span class="original-price">$29.99</span>
                    </div>
                    <button class="add-to-cart" data-product-id="10">Add to Cart</button>
                </div>
            </div>
            
            <!-- Related Product 3 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/product-11.jpg" alt="Action Figure Display Case">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="11">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="11"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=11">Action Figure Display Case</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <span>(8 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">$34.99</span>
                    </div>
                    <button class="add-to-cart" data-product-id="11">Add to Cart</button>
                </div>
            </div>
            
            <!-- Related Product 4 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/product-12.jpg" alt="Robot Accessory Pack">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="12">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="12"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=12">Robot Accessory Pack</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <span>(12 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">$14.99</span>
                    </div>
                    <button class="add-to-cart" data-product-id="12">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Enhanced Product Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Image Viewer Functionality
    const mainImage = document.getElementById('main-product-image');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Update main image
            const newSrc = this.getAttribute('data-image');
            if (mainImage) {
                mainImage.style.opacity = '0.5';
                setTimeout(() => {
                    mainImage.src = newSrc;
                    mainImage.style.opacity = '1';
                }, 150);
            }
        });
    });
    
    // Color Option Selection
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            colorOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Tab Functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Add active class to clicked button and corresponding pane
            this.classList.add('active');
            const targetPane = document.getElementById(targetTab);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
    
    // Quantity Input Validation
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            const min = parseInt(this.getAttribute('min'));
            const max = parseInt(this.getAttribute('max'));
            let value = parseInt(this.value);
            
            if (value < min) this.value = min;
            if (value > max) this.value = max;
        });
    }
    
    // Add to Cart Animation
    const addToCartBtn = document.querySelector('.product-actions .btn-primary');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function(e) {
            // Add loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            this.disabled = true;
            
            // Trigger confetti
            if (typeof launchConfetti === 'function') {
                launchConfetti();
            }
            
            // Reset button after 2 seconds (you can remove this in production)
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 2000);
        });
    }
    
    // Image Zoom on Hover (Desktop)
    if (window.innerWidth > 768) {
        const mainImageContainer = document.querySelector('.main-image');
        if (mainImageContainer) {
            mainImageContainer.addEventListener('mouseenter', function() {
                this.style.cursor = 'zoom-in';
            });
            
            mainImageContainer.addEventListener('mouseleave', function() {
                this.style.cursor = 'default';
            });
        }
    }
});
</script>

<?php
// Include footer
include '../includes/footer.php';
?> 