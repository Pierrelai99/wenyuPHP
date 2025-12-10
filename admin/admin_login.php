<?php
session_start();
require_once '../includes/db.php';

// If already logged in as admin, redirect to dashboard
if (isset($_SESSION['user_code']) && $_SESSION['role'] === 'admin') {
    header('Location: dashboard.php');
    exit();
}


$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            // Only check for admin users
            $stmt = $pdo->prepare("SELECT user_code, user_name, pwd_hash, user_role, email 
                FROM seafood_users 
                WHERE user_name = ? AND user_role = 'admin'");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && $password === $admin['pwd_hash']) {
                $_SESSION['user_code'] = $admin['user_code'];
                $_SESSION['username']  = $admin['user_name'];
                $_SESSION['email']     = $admin['email'];
                $_SESSION['role']      = $admin['user_role'];

                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Invalid admin credentials.';
            }
        } catch (PDOException $e) {
            $error = 'A system error occurred. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Fresh Catch Seafood</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <section class="login-section">
        <div class="container">
            <div class="login-layout">
                <div class="login-form-container">
                    <div class="form-header">
                        <h1>Seafood Stall Admin</h1>
                        <p>Fresh catch management portal</p>
                        <?php if ($error): ?>
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <form class="login-form" method="POST" action="">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                            <i class="fas fa-lock"></i>
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-sign-in-alt"></i> Access Dashboard
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordToggle = document.querySelector('.password-toggle');
        if (passwordToggle) {
            passwordToggle.addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    });
    </script>
</body>
</html>
