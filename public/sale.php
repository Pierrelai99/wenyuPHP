<?php
session_start();

// Page variables
$page_title = "Sale";
$page_description = "Fresh seafood deals - up to 70% off on premium catches!";
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
            <h1>Fresh Catch Flash Sale</h1>
            <p>Up to 70% off on premium seafood today</p>
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
                    <img src="../assets/images/categories/seafood_fish.png" alt="Fresh Fish Sale">
                </div>
                <div class="category-content">
                    <h3>Fresh Fish</h3>
                    <p>Up to 50% off</p>
                    <a href="products.php?category=fresh-fish&sale=1" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            
            <div class="sale-category">
                <div class="category-image">
                    <img src="../assets/images/categories/seafood_shellfish.png" alt="Shellfish Sale">
                </div>
                <div class="category-content">
                    <h3>Shellfish</h3>
                    <p>Up to 60% off</p>
                    <a href="products.php?category=shellfish&sale=1" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            
            <div class="sale-category">
                <div class="category-image">
                    <img src="../assets/images/categories/seafood_prawn.png" alt="Prawns & Shrimp Sale">
                </div>
                <div class="category-content">
                    <h3>Prawns & Shrimp</h3>
                    <p>Up to 40% off</p>
                    <a href="products.php?category=prawns-shrimp&sale=1" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            
            <div class="sale-category">
                <div class="category-image">
                    <img src="../assets/images/categories/frozen_seafood.png" alt="Frozen Seafood Sale">
                </div>
                <div class="category-content">
                    <h3>Frozen Seafood</h3>
                    <p>Up to 70% off</p>
                    <a href="products.php?category=frozen-seafood&sale=1" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Sale Products Section -->
<section class="featured-sale-products-section">
    <div class="container">
        <div class="section-header">
            <h2>Today's Fresh Deals</h2>
            <p>Don't miss these amazing seafood specials!</p>
        </div>
        
        <div class="products-grid">
            <!-- Sale Product 1 -->
            <div class="product-card sale-product">
                <div class="product-image">
                    <img src="../assets/images/products/salmonfillet.png" alt="Norwegian Salmon Fillet">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="1">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="1"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge sale">50% OFF</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=1">Norwegian Salmon Fillet</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>(24 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">RM42.90/kg</span>
                        <span class="original-price">RM48.90/kg</span>
                        <span class="discount-amount">Save RM6.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="1">Add to Cart</button>
                </div>
            </div>
            
            <!-- Sale Product 2 -->
            <div class="product-card sale-product">
                <div class="product-image">
                    <img src="../assets/images/products/tigerprawn.png" alt="Fresh Tiger Prawns">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="4">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="4"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge sale">25% OFF</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=7">Fresh Tiger Prawns</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>(28 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">RM34.90/kg</span>
                        <span class="original-price">RM39.90/kg</span>
                        <span class="discount-amount">Save RM5.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="4">Add to Cart</button>
                </div>
            </div>
            
            <!-- Sale Product 3 -->
            <div class="product-card sale-product">
                <div class="product-image">
                    <img src="../assets/images/products/mudcrabs.png" alt="Live Mud Crabs">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="10">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="10"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge sale">30% OFF</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=10">Live Mud Crabs</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>(21 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">RM22.99/kg</span>
                        <span class="original-price">RM32.99/kg</span>
                        <span class="discount-amount">Save RM10.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="10">Add to Cart</button>
                </div>
            </div>
            
            <!-- Sale Product 4 -->
            <div class="product-card sale-product">
                <div class="product-image">
                    <img src="../assets/images/products/seafood_platter.png" alt="Seafood Party Platter">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="5">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="5"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge sale">40% OFF</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=5">Seafood Party Platter</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>(22 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">RM89.99</span>
                        <span class="original-price">RM149.99</span>
                        <span class="discount-amount">Save RM60.00</span>
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
            <h2>Flash Sale - Limited Fresh Stock!</h2>
            <p>These deals won't last long. Order now before they're gone!</p>
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
                    <img src="../assets/images/products/fresh_squids.png" alt="Fresh Squid">
                    <div class="flash-badge">FLASH SALE</div>
                </div>
                <div class="flash-product-content">
                    <h3>Fresh Squid</h3>
                    <div class="flash-price">
                        <span class="current-price">RM9.99/kg</span>
                        <span class="original-price">RM24.99/kg</span>
                        <span class="discount-percent">60% OFF</span>
                    </div>
                    <button class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
            
            <!-- Flash Sale Product 2 -->
            <div class="flash-product">
                <div class="flash-product-image">
                    <img src="../assets/images/products/oyster.png" alt="Oysters (Dozen)">
                    <div class="flash-badge">FLASH SALE</div>
                </div>
                <div class="flash-product-content">
                    <h3>Oysters (Dozen)</h3>
                    <div class="flash-price">
                        <span class="current-price">RM12.99</span>
                        <span class="original-price">RM19.99</span>
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
            <p>Frozen seafood - final sale, no returns</p>
        </div>
        
        <div class="products-grid">
            <!-- Clearance Product 1 -->
            <div class="product-card clearance-product">
                <div class="product-image">
                    <img src="../assets/images/products/cod_fillets.png" alt="Frozen Cod Fillets">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="13">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="13"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge clearance">CLEARANCE</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=13">Frozen Cod Fillets</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <span>(8 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">RM15.99/kg</span>
                        <span class="original-price">RM39.99/kg</span>
                        <span class="discount-amount">Save RM24.00</span>
                    </div>
                    <button class="add-to-cart" data-product-id="13">Add to Cart</button>
                </div>
            </div>
            
            <!-- Clearance Product 2 -->
            <div class="product-card clearance-product">
                <div class="product-image">
                    <img src="../assets/images/products/mixed_seafood_pack.png" alt="Mixed Seafood Pack">
                    <div class="product-overlay">
                        <button class="quick-view" data-product-id="14">Quick View</button>
                        <button class="add-to-wishlist" data-product-id="14"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-badge clearance">CLEARANCE</div>
                </div>
                <div class="product-content">
                    <h3><a href="product.php?id=14">Mixed Seafood Pack</a></h3>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <span>(12 reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">RM8.99/kg</span>
                        <span class="original-price">RM24.99/kg</span>
                        <span class="discount-amount">Save RM16.00</span>
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
                <li>Sale prices are valid until the end of the promotion period or while stocks last</li>
                <li>Limited quantities available - fresh seafood subject to daily availability</li>
                <li>Clearance items (frozen seafood) are final sale - no returns or exchanges</li>
                <li>Sale prices cannot be combined with other promotions</li>
                <li>Free delivery applies to orders over RM50 (after discounts, within 10km radius)</li>
                <li>Prices and availability subject to change without notice</li>
                <li>All seafood sold by weight - actual weight may vary slightly</li>
                <li>Fresh seafood should be consumed or properly stored within 24 hours of delivery</li>
            </ul>
        </div>
    </div>
</section>

<?php
// Include footer
include '../includes/footer.php';
?>