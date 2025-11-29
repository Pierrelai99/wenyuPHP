<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Login</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { padding: 8px; width: 250px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Quick Login Test</h2>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="success">
            <h3>✅ You are logged in!</h3>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
            
            <p><a href="dashboard.php">Go to Dashboard</a></p>
            <p><a href="../public/logout.php">Logout</a></p>
        </div>
    <?php else: ?>
        <div class="error">
            <p>❌ You are not logged in.</p>
        </div>
        
        <form action="../public/auth.php" method="POST">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label>Email (try: admin@toylandstore.com)</label>
                <input type="email" name="email" value="admin@toylandstore.com" required>
            </div>
            
            <div class="form-group">
                <label>Password (try: admin123)</label>
                <input type="password" name="password" value="admin123" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <p><a href="../public/login.php">Go to Full Login Page</a></p>
    <?php endif; ?>
    
    <hr>
    <h3>Navigation Test:</h3>
    <ul>
        <li><a href="../index.php">Home</a></li>
        <li><a href="../public/login.php">Login Page</a></li>
        <li><a href="../public/register.php">Register Page</a></li>
        <li><a href="dashboard.php">Member Dashboard</a></li>
        <li><a href="../admin/dashboard.php">Admin Dashboard</a></li>
    </ul>
</body>
</html>
