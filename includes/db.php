<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Af@12345678'); // Your MySQL password
define('DB_NAME', 'dbassignment');

// Create connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USERNAME,
        DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to generate unique user code
function generateUserCode($pdo) {
    $stmt = $pdo->query("SELECT user_code FROM seafood_users ORDER BY user_code DESC LIMIT 1");
    $row = $stmt->fetch();

    if ($row) {
        $num = intval(substr($row['user_code'], 3)) + 1;
    } else {
        $num = 1;
    }

    return "USR" . str_pad($num, 8, '0', STR_PAD_LEFT);
}

// Function to check if email already exists
function emailExists($pdo, $email) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM seafood_users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
}

// Function to check if username already exists
function usernameExists($pdo, $username) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM seafood_users WHERE user_name = ?");
    $stmt->execute([$username]);
    return $stmt->fetchColumn() > 0;
}
?>
