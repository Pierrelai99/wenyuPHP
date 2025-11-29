<?php
session_start();

// Page variables
$page_title = "Shopping Cart";
$page_description = "Review and manage your shopping cart items";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'cart.php', 'title' => 'Shopping Cart']
];

// Include header
include '../includes/header.php';
?>

<!-- Cart Section -->
<section class="cart-section">
    <div class="container">
        <div class="cart-header">
            <h1>Shopping Cart</h1>
            <p>Review your items and proceed to checkout</p>
        </div>
        
        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items">
                <div class="cart-table-header">
                    <div class="header-product">Product</div>
                    <div class="header-price">Price</div>
                    <div class="header-quantity">Quantity</div>
                    <div class="header-total">Total</div>
                    <div class="header-actions">Actions</div>
                </div>
                
                <div class="cart-items-list" id="cart-items-container">
                    <!-- Cart Item 1 -->
                    <div class="cart-item" data-product-id="1">
                        <div class="item-product">
                            <div class="item-image">
                                <img src="../assets/images/ironman_mark85_1.png" alt="Super Robot Action Figure">
                            </div>
                            <div class="item-details">
                                <h3><a href="product.php?id=1">Super Robot Action Figure</a></h3>
                                <p class="item-sku">SKU: ROB-001</p>
                                <div class="item-options">
                                    <span class="option">Color: Blue</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="item-price">
                            <span class="current-price">MYR44.99</span>
                            <span class="original-price">MYR94.99</span>
                        </div>
                        
                        <div class="item-quantity">
                            <div class="quantity-selector">
                                <button class="quantity-btn minus" data-product-id="1">-</button>
                                <input type="number" class="quantity-input" value="2" min="1" max="10" data-product-id="1">
                                <button class="quantity-btn plus" data-product-id="1">+</button>
                            </div>
                        </div>
                        
                        <div class="item-total">
                            <span class="total-price">MYR49.98</span>
                        </div>
                        
                        <div class="item-actions">
                            <button class="remove-item" data-product-id="1">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="save-for-later" data-product-id="1">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Cart Item 2 -->
                    <div class="cart-item" data-product-id="2">
                        <div class="item-product">
                            <div class="item-image">
                                <img src="../assets/images/games_puzzles.png" alt="Educational Building Blocks">
                            </div>
                            <div class="item-details">
                                <h3><a href="product.php?id=2">Educational Building Blocks</a></h3>
                                <p class="item-sku">SKU: BLK-002</p>
                                <div class="item-options">
                                    <span class="option">Size: Large Set</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="item-price">
                            <span class="current-price">MYR99.99</span>
                        </div>
                        
                        <div class="item-quantity">
                            <div class="quantity-selector">
                                <button class="quantity-btn minus" data-product-id="2">-</button>
                                <input type="number" class="quantity-input" value="1" min="1" max="10" data-product-id="2">
                                <button class="quantity-btn plus" data-product-id="2">+</button>
                            </div>
                        </div>
                        
                        <div class="item-total">
                            <span class="total-price">MYR139.99</span>
                        </div>
                        
                        <div class="item-actions">
                            <button class="remove-item" data-product-id="2">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="save-for-later" data-product-id="2">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Cart Item 3 -->
                    <div class="cart-item" data-product-id="3">
                        <div class="item-product">
                            <div class="item-image">
                                <img src="../assets/images/category-games-puzzles.jpg" alt="Family Board Game">
                            </div>
                            <div class="item-details">
                                <h3><a href="product.php?id=3">Family Board Game</a></h3>
                                <p class="item-sku">SKU: GAM-003</p>
                                <div class="item-options">
                                    <span class="option">Edition: Deluxe</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="item-price">
                            <span class="current-price">MYR69.99</span>
                        </div>
                        
                        <div class="item-quantity">
                            <div class="quantity-selector">
                                <button class="quantity-btn minus" data-product-id="3">-</button>
                                <input type="number" class="quantity-input" value="1" min="1" max="10" data-product-id="3">
                                <button class="quantity-btn plus" data-product-id="3">+</button>
                            </div>
                        </div>
                        
                        <div class="item-total">
                            <span class="total-price">MYR229.99</span>
                        </div>
                        
                        <div class="item-actions">
                            <button class="remove-item" data-product-id="3">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="save-for-later" data-product-id="3">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Empty Cart Message -->
                <div class="empty-cart" id="empty-cart" style="display: none;">
                    <div class="empty-cart-content">
                        <i class="fas fa-shopping-cart"></i>
                        <h2>Your cart is empty</h2>
                        <p>Looks like you haven't added any items to your cart yet.</p>
                        <a href="products.php" class="btn btn-primary">Start Shopping</a>
                    </div>
                </div>
                
                <!-- Cart Actions -->
                <div class="cart-actions">
                    <div class="cart-actions-left">
                        <a href="products.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                        <button class="btn btn-outline" id="update-cart">
                            <i class="fas fa-sync-alt"></i> Update Cart
                        </button>
                        <button class="btn btn-outline" id="clear-cart">
                            <i class="fas fa-trash"></i> Clear Cart
                        </button>
                    </div>
                    
                    <div class="cart-actions-right">
                        <button class="btn btn-outline" id="save-cart">
                            <i class="far fa-heart"></i> Save for Later
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="cart-summary">
                <div class="summary-header">
                    <h3>Order Summary</h3>
                </div>
                
                <div class="summary-content">
                    <div class="summary-row">
                        <span>Subtotal (4 items):</span>
                        <span>MYR119.96</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span class="shipping-cost">MYR5.99</span>
                    </div>
                    
                    <div class="summary-row discount">
                        <span>Discount:</span>
                        <span>-MYR10.00</span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>MYR115.95</span>
                    </div>
                </div>
                
                <!-- Promo Code -->
                <div class="promo-code">
                    <h4>Have a Promo Code?</h4>
                    <div class="promo-form">
                        <input type="text" placeholder="Enter promo code" id="promo-code">
                        <button type="button" class="btn btn-outline" id="apply-promo">Apply</button>
                    </div>
                </div>
                
                <!-- Checkout Button -->
                <div class="checkout-section">
                    <button class="btn btn-primary btn-large" id="proceed-checkout">
                        <i class="fas fa-lock"></i> Proceed to Checkout
                    </button>
                    <p class="secure-checkout">
                        <i class="fas fa-shield-alt"></i> Secure checkout powered by SSL
                    </p>
                </div>
                
                <!-- Payment Methods -->
                <div class="payment-methods">
                    <h4>We Accept:</h4>
                    <div class="payment-icons">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-amex"></i>
                        <i class="fab fa-cc-paypal"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Saved Items -->
        <div class="saved-items-section">
            <h3>Saved for Later (2 items)</h3>
            <div class="saved-items-grid">
                <!-- Saved Item 1 -->
                <div class="saved-item" data-product-id="4">
                    <div class="saved-item-image">
                        <img src="assets/images/product-4.jpg" alt="Art & Craft Kit">
                    </div>
                    <div class="saved-item-details">
                        <h4><a href="product.php?id=4">Art & Craft Kit</a></h4>
                        <p class="saved-item-price">MYR19.99</p>
                        <button class="btn btn-outline btn-small move-to-cart" data-product-id="4">
                            Move to Cart
                        </button>
                        <button class="btn btn-outline btn-small remove-saved" data-product-id="4">
                            Remove
                        </button>
                    </div>
                </div>
                
                <!-- Saved Item 2 -->
                <div class="saved-item" data-product-id="5">
                    <div class="saved-item-image">
                        <img src="assets/images/product-5.jpg" alt="Outdoor Play Set">
                    </div>
                    <div class="saved-item-details">
                        <h4><a href="product.php?id=5">Outdoor Play Set</a></h4>
                        <p class="saved-item-price">MYR89.99</p>
                        <button class="btn btn-outline btn-small move-to-cart" data-product-id="5">
                            Move to Cart
                        </button>
                        <button class="btn btn-outline btn-small remove-saved" data-product-id="5">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recommended Products -->
        <div class="recommended-products">
            <h3>You Might Also Like</h3>
            <div class="products-grid">
                <!-- Recommended Product 1 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/images/product-6.jpg" alt="Baby Rattle Set">
                        <div class="product-overlay">
                            <button class="quick-view" data-product-id="6">Quick View</button>
                            <button class="add-to-wishlist" data-product-id="6"><i class="far fa-heart"></i></button>
                        </div>
                    </div>
                    <div class="product-content">
                        <h3><a href="product.php?id=6">Baby Rattle Set</a></h3>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <span>(12 reviews)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">MYR14.99</span>
                        </div>
                        <button class="add-to-cart" data-product-id="6">Add to Cart</button>
                    </div>
                </div>
                
                <!-- Recommended Product 2 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/images/product-7.jpg" alt="Science Experiment Kit">
                        <div class="product-overlay">
                            <button class="quick-view" data-product-id="7">Quick View</button>
                            <button class="add-to-wishlist" data-product-id="7"><i class="far fa-heart"></i></button>
                        </div>
                        <div class="product-badge sale">Sale</div>
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
                            <span class="current-price">MYR44.99</span>
                            <span class="original-price">MYR59.99</span>
                        </div>
                        <button class="add-to-cart" data-product-id="7">Add to Cart</button>
                    </div>
                </div>
                
                <!-- Recommended Product 3 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/images/product-8.jpg" alt="Puzzle Set">
                        <div class="product-overlay">
                            <button class="quick-view" data-product-id="8">Quick View</button>
                            <button class="add-to-wishlist" data-product-id="8"><i class="far fa-heart"></i></button>
                        </div>
                    </div>
                    <div class="product-content">
                        <h3><a href="product.php?id=8">Puzzle Set</a></h3>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <span>(19 reviews)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">MYR16.99</span>
                        </div>
                        <button class="add-to-cart" data-product-id="8">Add to Cart</button>
                    </div>
                </div>
                
                <!-- Recommended Product 4 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/images/product-9.jpg" alt="Robot Companion Figure">
                        <div class="product-overlay">
                            <button class="quick-view" data-product-id="9">Quick View</button>
                            <button class="add-to-wishlist" data-product-id="9"><i class="far fa-heart"></i></button>
                        </div>
                    </div>
                    <div class="product-content">
                        <h3><a href="product.php?id=9">Robot Companion Figure</a></h3>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <span>(16 reviews)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">MYR19.99</span>
                        </div>
                        <button class="add-to-cart" data-product-id="9">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';
?> 