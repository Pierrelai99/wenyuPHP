<?php
session_start();

// Page variables
$page_title = "Shopping Cart";
$page_description = "Review and manage your shopping cart items";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'cart.php', 'title' => 'Shopping Cart']
];

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ADD ITEM
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $id = $_POST['product_id'];
        $name = $_POST['name'];
        $price = floatval($_POST['price']);
        $sku = $_POST['sku'];
        $image = $_POST['image'];
        $qty = 1;

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += 1;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'sku' => $sku,
                'image' => $image,
                'qty' => $qty
            ];
        }
    }

    // UPDATE QUANTITY
    if (isset($_POST['action']) && ($_POST['action'] === 'plus' || $_POST['action'] === 'minus')) {
        $id = $_POST['product_id'];
        if (isset($_SESSION['cart'][$id])) {
            if ($_POST['action'] === 'plus') {
                $_SESSION['cart'][$id]['qty'] += 1;
            } elseif ($_POST['action'] === 'minus' && $_SESSION['cart'][$id]['qty'] > 1) {
                $_SESSION['cart'][$id]['qty'] -= 1;
            }
        }
    }

    // REMOVE ITEM
    if (isset($_POST['action']) && $_POST['action'] === 'remove') {
        unset($_SESSION['cart'][$_POST['product_id']]);
    }

    // CLEAR ALL
    if (isset($_POST['action']) && $_POST['action'] === 'clear') {
        unset($_SESSION['cart']);
    }

    header("Location: cart.php");
    exit;
}


//---------------------
// CART CALCULATIONS
//---------------------
$subtotal = 0;
$item_count = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['qty'];
        $item_count += $item['qty'];
    }
}

$shipping = 5.99;       // Later can be dynamic
$discount = 0.00;       // Set to 0 until promo function done
$total = $subtotal + $shipping - $discount;


// Include header
include '../includes/header.php';
?>

<section class="cart-section">
    <div class="container">
        <div class="cart-header">
            <h1>Shopping Cart</h1>
            <p>Review your items and proceed to checkout</p>
        </div>

        <div class="cart-layout">

            <!-- CART ITEMS -->
            <div class="cart-items">
                <div class="cart-table-header">
                    <div class="header-product">Product</div>
                    <div class="header-price">Price</div>
                    <div class="header-quantity">Quantity</div>
                    <div class="header-total">Total</div>
                    <div class="header-actions">Actions</div>
                </div>

                <div class="cart-items-list" id="cart-items-container">
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="cart-item">

                                <div class="item-product">
                                    <div class="item-image">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    </div>
                                    <div class="item-details">
                                        <h3><a href="product.php?id=<?= $item['id'] ?>"><?= $item['name'] ?></a></h3>
                                        <p class="item-sku">SKU: <?= $item['sku'] ?></p>
                                    </div>
                                </div>

                                <div class="item-price">
                                    MYR<?= number_format($item['price'], 2) ?>
                                </div>

                                <div class="item-quantity">
                                    <form method="POST">
                                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                        <button name="action" value="minus" class="quantity-btn">-</button>
                                        <input type="number" value="<?= $item['qty'] ?>" readonly>
                                        <button name="action" value="plus" class="quantity-btn">+</button>
                                    </form>
                                </div>

                                <div class="item-total">
                                    MYR<?= number_format($item['price'] * $item['qty'], 2) ?>
                                </div>

                                <div class="item-actions">
                                    <form method="POST">
                                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="action" value="remove">
                                        <button class="remove-item"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-cart">
                            <h2>Your cart is empty.</h2>
                            <a href="products.php" class="btn btn-primary">Start Shopping</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="cart-actions">
                    <form method="POST">
                        <input type="hidden" name="action" value="clear">
                        <button class="btn btn-outline"><i class="fas fa-trash"></i> Clear Cart</button>
                    </form>
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="cart-summary">
                <div class="summary-header">
                    <h3>Order Summary</h3>
                </div>

                <div class="summary-content">
                    <div class="summary-row">
                        <span>Subtotal (<?= $item_count ?> items):</span>
                        <span>MYR<?= number_format($subtotal, 2) ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>MYR<?= number_format($shipping, 2) ?></span>
                    </div>

                    <div class="summary-row discount">
                        <span>Discount:</span>
                        <span>-MYR<?= number_format($discount, 2) ?></span>
                    </div>

                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>MYR<?= number_format($total, 2) ?></span>
                    </div>
                </div>

                <button class="btn btn-primary btn-large">
                    Proceed to Checkout
                </button>
            </div>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
