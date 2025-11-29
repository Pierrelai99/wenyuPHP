<?php
session_start();

// Page variables
$page_title = "Login";
$page_description = "Sign in to your Seafood Market account";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'login.php', 'title' => 'Login']
];

// Include header
include '../includes/header.php';
?>

<!-- Login Section -->
<section class="login-section">
    <div class="container">
        <div class="login-layout">

            <!-- Login Form -->
            <div class="login-form-container">
                <div class="form-header">
                    <h1>Welcome Back</h1>
                    <p>Sign in to access fresh seafood deals</p>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form class="login-form" action="auth.php" method="POST">
                    <input type="hidden" name="action" value="login">

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                        <i class="fas fa-envelope"></i>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-lock"></i>
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="checkmark"></span>
                            Remember me
                        </label>
                        <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="signup-link">
                    <p>Don't have an account? <a href="register.php">Create one now</a></p>
                </div>
            </div>

            <!-- Right Side: Seafood Benefits -->
            <div class="login-benefits seafood-theme">
                <div class="benefits-header">
                    <h2>Fresh Seafood. Better Experience.</h2>
                    <p>Why customers love our seafood store</p>
                </div>

                <div class="benefits-list">

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-fish"></i>
                        </div>
                        <div class="benefit-content">
                            <h3>Fresh Daily Catch</h3>
                            <p>Get the freshest seafood straight from the harbor.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-ship"></i>
                        </div>
                        <div class="benefit-content">
                            <h3>Trusted Suppliers</h3>
                            <p>We work only with certified local fishermen.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-ice-cream"></i>
                        </div>
                        <div class="benefit-content">
                            <h3>Cold Chain Guaranteed</h3>
                            <p>Your seafood stays fresh from sea to door.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="benefit-content">
                            <h3>Member Discounts</h3>
                            <p>Exclusive deals for registered members.</p>
                        </div>
                    </div>

                </div>

                <!-- Customer Testimonial -->
                <div class="customer-testimonial">
                    <div class="testimonial-content">
                        <p>"Best seafood delivery ever. Super fresh and fast delivery!"</p>
                        <div class="testimonial-author">
                            <div class="customer-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div>
                                <h4>Michael Tan</h4>
                                <span>Verified Customer</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- Security Notice -->
<section class="security-notice">
    <div class="container">
        <div class="security-content">
            <div class="security-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="security-text">
                <h3>Your Security Matters</h3>
                <p>Your login is protected by strong encryption. We do not share your information.</p>
            </div>
        </div>
    </div>
</section>

<script>
// Password toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.password-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
});
</script>

<?php
include '../includes/footer.php';
?>
