<?php
session_start();

echo "<h2>Debug Information</h2>";
echo "<p><strong>Current Directory:</strong> " . basename(dirname($_SERVER['SCRIPT_NAME'])) . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

$current_dir = basename(dirname($_SERVER['SCRIPT_NAME']));
$is_subdirectory = in_array($current_dir, ['public', 'member', 'admin']);
$assets_path = $is_subdirectory ? '../assets' : 'assets';
$root_path = $is_subdirectory ? '../' : '';

echo "<p><strong>Is Subdirectory:</strong> " . ($is_subdirectory ? 'Yes' : 'No') . "</p>";
echo "<p><strong>Assets Path:</strong> " . $assets_path . "</p>";
echo "<p><strong>Root Path:</strong> " . $root_path . "</p>";

echo "<h3>Session Data:</h3>";
if (isset($_SESSION['user_id'])) {
    echo "<p>✅ User is logged in</p>";
    echo "<p><strong>User ID:</strong> " . htmlspecialchars($_SESSION['user_id']) . "</p>";
    echo "<p><strong>Username:</strong> " . htmlspecialchars($_SESSION['username']) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($_SESSION['email']) . "</p>";
    echo "<p><strong>Role:</strong> " . htmlspecialchars($_SESSION['role']) . "</p>";
} else {
    echo "<p>❌ User is not logged in</p>";
}

echo "<h3>CSS File Check:</h3>";
$css_file = $assets_path . '/css/style.css';
echo "<p><strong>CSS File Path:</strong> " . $css_file . "</p>";

if (file_exists(__DIR__ . '/' . $css_file)) {
    echo "<p>✅ CSS file exists</p>";
} else {
    echo "<p>❌ CSS file does not exist at: " . __DIR__ . '/' . $css_file . "</p>";
}

echo "<h3>Links Test:</h3>";
echo '<p><a href="' . $root_path . 'index.php">Home Link Test</a></p>';
echo '<p><a href="' . $root_path . 'public/logout.php">Logout Link Test</a></p>';
?>
