<?php
session_start();

// Page variables
$page_title = "Sale";
$page_description = "Amazing deals on toys and games - up to 70% off!";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'sale.php', 'title' => 'Sale']
];

// Include header
include '../includes/header.php';
?>

<!-- Sale Hero Section -->
<section class="sale-hero-section">
    <div class="container">
        <div class="sale-hero-content">
            <h1>Mega Sale Event</h1>
            <p>Up to 70% off on selected toys and games</p>
            <div class="sale-countdown">
                <div class="countdown-item">
                    <span class="countdown-number" id="days">02</span>
                    <span class="countdown-label">Days</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="hours">18</span>
                    <span class="countdown-label">Hours</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="minutes">45</span>
                    <span class="countdown-label">Minutes</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="seconds">30</span>
                    <span class="countdown-label">Seconds</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sale Categories Section -->
<section class="sale-categories-section">
    <div class="container">
        <div class="sale-categories-grid">
            <div class="sale-category">
                <div class="category-image">
                    <img src="assets/images/sale-action-figures.jpg" alt="Action Figures Sale">
                </div>
                <div class="category-content">
                    <h3>Action Figures</h3>
                    <p>Up to 50% off</p>
                    <a href="products.php?category=action-figures&sale=1" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            
            <div class="sale-category">
                <div class="category-image">
                    <img src="assets/images/sale-board-games.jpg" alt="Board Games Sale">
                </div>
                <div class="category-content">
                    <h3>Board Games</h3>
                    <p>Up to 60% off</p>
                    <a href="products.php?category=board-games&sale=1" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            
            <div class="sale-category">
                <div class="category-image">
                    <img src="assets/images/sale-educational.jpg" alt="Educational Toys Sale">
                </div>
                <div class="category-content">
                    <h3>Educational Toys</h3>
                    <p>Up to 40% off</p>
                    <a href="products.php?category=educational&sale=1" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            
            <div class="sale-category">
                <div class="category-image">
                    <img src="assets/images/sale-outdoor.jpg" alt="Outdoor Toys Sale">
                </div>
                <div class="category-content">
                    <h3>Outdoor Toys</h3>
                    <p>Up to 70% off</p>
                    <a href="products.php?category=outdoor&sale=1" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Sale Products Section -->
<section class="featured-sale-products-section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Sale Items</h2>
            <p>Don't miss these amazing deals!</p>
        </div>
        
        <div class="products-grid">
            <!-- Sale Product 1 -->
            <div class="product-card sale-product">
                <div class="product-image">
                    <img src="assets/images/product-1.jpg" alt="Super Robot Action Figure">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="1">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="1"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge sale">50% OFF</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=1">Super Robot Action Figure</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>(24 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">$24.99</span>
                        <span class="original-price">$49.99</span>
                        <span class="discount-amount">Save $25.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="1">Add to Cart</button>
                </div>
            </div>
            
            <!-- Sale Product 2 -->
            <div class="product-card sale-product">
                <div class="product-image">
                    <img src="assets/images/product-7.jpg" alt="Science Experiment Kit">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="7">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="7"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge sale">25% OFF</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=7">Science Experiment Kit</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>(28 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">$44.99</span>
                        <span class="original-price">$59.99</span>
                        <span class="discount-amount">Save $15.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="7">Add to Cart</button>
                </div>
            </div>
            
            <!-- Sale Product 3 -->
            <div class="product-card sale-product">
                <div class="product-image">
                    <img src="assets/images/product-10.jpg" alt="Space Warrior Figure">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="10">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="10"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge sale">30% OFF</div>
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
                        <span class="original-price">$32.99</span>
                        <span class="discount-amount">Save $10.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="10">Add to Cart</button>
                </div>
            </div>
            
            <!-- Sale Product 4 -->
            <div class="product-card sale-product">
                <div class="product-image">
                    <img src="assets/images/product-5.jpg" alt="Outdoor Play Set">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="5">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="5"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge sale">40% OFF</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=5">Outdoor Play Set</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>(22 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">$89.99</span>
                        <span class="original-price">$149.99</span>
                        <span class="discount-amount">Save $60.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="5">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Flash Sale Section -->
<section class="flash-sale-section">
    <div class="container">
        <div class="flash-sale-content">
            <h2>Flash Sale - Limited Time!</h2>
            <p>These deals won't last long. Shop now before they're gone!</p>
            <div class="flash-countdown">
                <span>Ends in: </span>
                <div class="countdown-item">
                    <span class="countdown-number" id="flash-hours">06</span>
                    <span class="countdown-label">Hours</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="flash-minutes">32</span>
                    <span class="countdown-label">Minutes</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="flash-seconds">15</span>
                    <span class="countdown-label">Seconds</span>
                </div>
            </div>
        </div>
        
        <div class="flash-products-grid">
            <!-- Flash Sale Product 1 -->
            <div class="flash-product">
                <div class="flash-product-image">
                    <img src="assets/images/product-12.jpg" alt="Robot Accessory Pack">
                    <div class="flash-badge">FLASH SALE</div>
                </div>
                <div class="flash-product-content">
                    <h3>Robot Accessory Pack</h3>
                    <div class="flash-price">
                        <span class="current-price">$9.99</span>
                        <span class="original-price">$24.99</span>
                        <span class="discount-percent">60% OFF</span>
                    </div>
                    <button class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
            
            <!-- Flash Sale Product 2 -->
            <div class="flash-product">
                <div class="flash-product-image">
                    <img src="assets/images/product-8.jpg" alt="Puzzle Set">
                    <div class="flash-badge">FLASH SALE</div>
                </div>
                <div class="flash-product-content">
                    <h3>Puzzle Set</h3>
                    <div class="flash-price">
                        <span class="current-price">$12.99</span>
                        <span class="original-price">$19.99</span>
                        <span class="discount-percent">35% OFF</span>
                    </div>
                    <button class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Clearance Section -->
<section class="clearance-section">
    <div class="container">
        <div class="section-header">
            <h2>Clearance Items</h2>
            <p>Final sale - no returns on clearance items</p>
        </div>
        
        <div class="products-grid">
            <!-- Clearance Product 1 -->
            <div class="product-card clearance-product">
                <div class="product-image">
                    <img src="assets/images/product-13.jpg" alt="Vintage Board Game">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="13">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="13"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge clearance">CLEARANCE</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=13">Vintage Board Game</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <span>(8 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">$15.99</span>
                        <span class="original-price">$39.99</span>
                        <span class="discount-amount">Save $24.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="13">Add to Cart</button>
                </div>
            </div>
            
            <!-- Clearance Product 2 -->
            <div class="product-card clearance-product">
                <div class="product-image">
                    <img src="assets/images/product-14.jpg" alt="Art Supply Set">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="14">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="14"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge clearance">CLEARANCE</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=14">Art Supply Set</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <span>(12 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">$8.99</span>
                        <span class="original-price">$24.99</span>
                        <span class="discount-amount">Save $16.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="14">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sale Terms Section -->
<section class="sale-terms-section">
    <div class="container">
        <div class="sale-terms-content">
            <h3>Sale Terms & Conditions</h3>
            <ul>
                <li>Sale prices are valid until the end of the promotion period</li>
                <li>Limited quantities available on select items</li>
                <li>Clearance items are final sale - no returns or exchanges</li>
                <li>Sale prices cannot be combined with other promotions</li>
                <li>Free shipping applies to orders over $50 (after discounts)</li>
                <li>Prices and availability subject to change without notice</li>
            </ul>
        </div>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';
?> 