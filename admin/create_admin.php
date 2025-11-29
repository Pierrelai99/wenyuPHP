<?php
require_once '../includes/db.php';

// Admin user details
$username = 'admin1';
$password = 'admin1';
$email = 'admin@toyland.com';
$role = 'admin';

try {
    // Check if admin already exists
    if (!usernameExists($pdo, $username)) {
        // Generate unique user ID
        $userId = getUniqueUserId($pdo);
        
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare insert statement
        $stmt = $pdo->prepare("INSERT INTO user (user_id, username, password, email, role) VALUES (?, ?, ?, ?, ?)");
        
        // Execute with admin user details
        $stmt->execute([$userId, $username, $hashedPassword, $email, $role]);
        
        echo "Admin user created successfully!";
    } else {
        echo "Admin user already exists!";
    }
} catch (PDOException $e) {
    echo "Error creating admin user: " . $e->getMessage();
}
?>
