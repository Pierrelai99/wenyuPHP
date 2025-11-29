<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

// Page variables
$page_title = "Member Dashboard";
$page_description = "Your personal ToyLand Store dashboard";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'dashboard.php', 'title' => 'Dashboard']
];

// Include header
include '../includes/header.php';
?>

<!-- Dashboard Section -->
<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header">
            <h1>🎉 Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>🧸 Manage your account and track your toy adventures</p>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="card-content">
                    <h3>My Orders</h3>
                    <p>View and track your order history</p>
                    <a href="orders.php" class="btn btn-primary">View Orders</a>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="card-content">
                    <h3>💖 Wishlist</h3>
                    <p>Save amazing toys for later purchase</p>
                    <a href="wishlist.php" class="btn btn-primary">View Wishlist</a>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-content">
                    <h3>⭐ My Reviews</h3>
                    <p>Share your toy experiences</p>
                    <a href="reviews.php" class="btn btn-primary">View Reviews</a>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="card-content">
                    <h3>👤 Account Settings</h3>
                    <p>Update your profile information</p>
                    <a href="profile.php" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>
        
        <div class="user-info">
            <h2>🎮 Account Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <strong>🆔 User ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?>
                </div>
                <div class="info-item">
                    <strong>👤 Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
                <div class="info-item">
                    <strong>📧 Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?>
                </div>
                <div class="info-item">
                    <strong>🎯 Role:</strong> <?php echo ucfirst($_SESSION['role']); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include '../includes/footer.php';
?>
