<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Af@12345678'); // Your MySQL password
// define('DB_PASSWORD', '');
define('DB_NAME', 'dbassignment');

$host = "localhost";
$dbname = "dbassignment";
$username = "root";
$password = "Af@12345678";

// Create connection
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to generate unique user code
function generateUserCode($pdo) {
    // Get latest code
    $stmt = $pdo->query("SELECT user_code FROM seafood_users ORDER BY user_code DESC LIMIT 1");
    $last = $stmt->fetchColumn();

    if ($last) {
        // Extract number part: USR00000025 → 25
        $num = intval(substr($last, 3));
        $num++;
    } else {
        $num = 1;
    }

    // Format back to USR00000001
    return "USR" . str_pad($num, 8, "0", STR_PAD_LEFT);
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
