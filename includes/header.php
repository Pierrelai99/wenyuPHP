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
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Toy Land</title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Where Fun Comes to Life! Discover toys that spark imagination and smiles'; ?>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/login.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/index.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/aboutUsContactUs.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <span>🚀 Fast Delivery</span>
                <span>🧸 Unique Characters</span>
                <span>🎁 Gift Ready</span>
            </div>
            <div class="top-bar-right">
                <a href="shipping.php">Free Shipping on Orders Over $50</a>
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
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="<?php echo $root_path; ?>member/dashboard.php">
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
                            <li><a href="<?php echo $root_path; ?>public/products.php?category=fish"><i class="fas fa-fish"></i> Fresh Fish</a></li>
                            <li><a href="<?php echo $root_path; ?>public/products.php?category=crustaceans"><i class="fas fa-shrimp"></i> Crustaceans</a></li>
                            <li><a href="<?php echo $root_path; ?>public/products.php?category=shellfish"><i class="fas fa-star"></i> Shellfish</a></li>
                            <li><a href="<?php echo $root_path; ?>public/products.php?category=prawns"><i class="fas fa-shrimp"></i> Prawns & Shrimp</a></li>
                            <li><a href="<?php echo $root_path; ?>public/products.php?category=crabs"><i class="fas fa-crab"></i> Crabs</a></li>
                            <li><a href="<?php echo $root_path; ?>public/products.php?category=lobsters"><i class="fas fa-fish"></i> Lobsters</a></li>
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
