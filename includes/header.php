<?php
// Determine the correct base path for assets
$current_dir = basename(dirname($_SERVER['SCRIPT_NAME']));
$is_subdirectory = in_array($current_dir, ['public', 'member', 'admin']);
$assets_path = $is_subdirectory ? '../assets' : 'assets';
$root_path = $is_subdirectory ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Fishy Wishy Seafood Store</title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Fresh Seafood Daily! Quality catches from ocean to your table'; ?>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/login.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/index.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/aboutUsContactUs.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/sale.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<?php
if (!isset($pdo)) {
    require_once __DIR__ . "/db.php";
}
?>
<?php
if (!isset($_SESSION['cart_count'])) {
    $_SESSION['cart_count'] = 0;
}

if (!empty($_SESSION['cart'])) {
    $_SESSION['cart_count'] = array_sum(
        array_column($_SESSION['cart'], 'qty')
    );
}
?>


<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <span>🐟 Fresh Daily</span>
                <span>🦐 Quality Guaranteed</span>
                <span>🎣 Locally Sourced</span>
            </div>
            <div class="top-bar-right">
                <a href="shipping.php">Free Delivery on Orders Over $50</a>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

 <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <a href="<?php echo $root_path; ?>index.php">
                        <i class="fas fa-fish"></i>
                        <div class="logo-text">
                            <h1>FishyWishy</h1>
                            <span>Fresh Seafood Delivered Daily!</span>
                        </div>
                    </a>
                </div>
                
                <div class="search-bar">
                    <form action="search.php" method="GET">
                        <input type="text" name="q" placeholder="Search for fresh fish, prawns, crabs, and more..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="header-actions">
                    <div class="user-account">
                        <?php if (isset($_SESSION['user_code'])): ?> 

                        <?php 
                            // If admin → go to admin dashboard
                            $dashboard_url = ($_SESSION['role'] === 'admin') 
                                            ? $root_path . "admin/dashboard.php" 
                                            : $root_path . "member/dashboard.php";
                        ?>

                        <a href="<?php echo $dashboard_url; ?>">
                            <i class="fas fa-user"></i> 
                            <span>My Account</span>
                        </a>

                        <a href="<?php echo $root_path; ?>public/logout.php">
                            <i class="fas fa-sign-out-alt"></i> 
                            <span>Logout</span>
                        </a>

                    <?php else: ?>

                            <a href="<?php echo $root_path; ?>public/login.php">
                                <i class="fas fa-sign-in-alt"></i> 
                                <span>Login</span>
                            </a>
                            <a href="<?php echo $root_path; ?>public/register.php">
                                <i class="fas fa-user-plus"></i> 
                                <span>Register</span>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="cart">
                        <a href="<?php echo $root_path; ?>public/cart.php" class="cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0; ?></span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="main-nav">
                <ul class="nav-menu">
                    <li><a href="<?php echo $root_path; ?>index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="dropdown">
                        <a href="<?php echo $root_path; ?>public/products.php">
                            <i class="fas fa-list"></i> Categories 
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                                $cats = $pdo->query("
                                    SELECT category_id, category_name 
                                    FROM seafood_categories 
                                    WHERE parent_id IS NULL AND status='active'
                                ")->fetchAll(PDO::FETCH_ASSOC);
                                ?>

                                <li class="dropdown">
                                    <a href="<?php echo $root_path; ?>public/products.php">
                                        <i class="fas fa-list"></i> Categories 
                                        <i class="fas fa-chevron-down"></i>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <?php foreach ($cats as $c): ?>
                                            <li>
                                                <a href="<?php echo $root_path; ?>public/products.php?category_id=<?= $c['category_id']; ?>">
                                                    <?= htmlspecialchars($c['category_name']); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                        </ul>
                    </li>
                    <li><a href="<?php echo $root_path; ?>public/products.php?filter=new"><i class="fas fa-sparkles"></i> Fresh Arrivals</a></li>
                    <li><a href="<?php echo $root_path; ?>public/sale.php"><i class="fas fa-tags"></i> Special Deals</a></li>
                    <li><a href="<?php echo $root_path; ?>public/products.php?filter=premium"><i class="fas fa-crown"></i> Premium Selection</a></li>
                    <li><a href="<?php echo $root_path; ?>public/about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                    <li><a href="<?php echo $root_path; ?>public/contact.php"><i class="fas fa-phone"></i> Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <!-- Breadcrumb -->
    <?php if (isset($show_breadcrumb) && $show_breadcrumb): ?>
    <div class="breadcrumb">
        <div class="container">
            <ul>
                <li><a href="<?php echo $root_path; ?>index.php">Home</a></li>
                <?php if (isset($breadcrumb_items)): ?>
                    <?php foreach ($breadcrumb_items as $item): ?>
                        <li><a href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <li class="current"><?php echo $page_title; ?></li>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <!-- ✅ ADD THIS PART HERE -->
<script>
$(document).on("submit", ".add-to-cart-form", function (e) {
    e.preventDefault();

    let form = $(this);
    let data = form.serialize();

    $.ajax({
        url: "<?php echo $root_path; ?>public/cart_ajax.php",
        type: "POST",
        data: data,
        dataType: "json", // 👈 force JSON
        success: function (response) {
            if (response.success) {
                $(".cart-count").text(response.cart_count);
                showToast("🛒 Added to cart!");
            } else {
                alert(response.msg ?? "Add to cart failed");
            }
        },
        error: function () {
            alert("AJAX error");
        }
    });
});

// Toast popup
function showToast(msg) {
    let toast = $("<div class='toast-msg'>" + msg + "</div>");
    $("body").append(toast);
    toast.fadeIn(300).delay(1200).fadeOut(300, function () {
        $(this).remove();
    });
}
</script>


<style>
.toast-msg {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 12px 18px;
    border-radius: 8px;
    z-index: 9999;
    font-size: 14px;
    display: none;
}
</style>

</body>
</html>