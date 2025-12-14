<?php
session_start();
require_once "../includes/db.php";

// Must be logged in & must be customer
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../public/login.php");
    exit();
}

$user_code = $_SESSION['user_code'];

/* ---------------------------------------------------------
   1. LOAD USER ACCOUNT + PROFILE
---------------------------------------------------------- */

$stmt = $pdo->prepare("
    SELECT 
        u.user_code, u.user_name, u.email, u.avatar_path,
        p.full_name, p.phone_no, p.dob, p.address,
        p.receive_updates, p.receive_promotions
    FROM seafood_users u
    LEFT JOIN seafood_user_profiles p ON u.user_code = p.user_code
    WHERE u.user_code = ?
");
$stmt->execute([$user_code]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "User profile not found.";
    header("Location: dashboard.php");
    exit();
}

/* ---------------------------------------------------------
   2. HANDLE UPDATE FORM SUBMISSION
---------------------------------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone_no']);
    $dob = $_POST['dob'];
    $address = trim($_POST['address']);

    // Checkbox values
    $updates = isset($_POST['receive_updates']) ? 1 : 0;
    $promotions = isset($_POST['receive_promotions']) ? 1 : 0;

    /* -----------------------------------
       ⚡ 2A. Handle Avatar Upload
    ------------------------------------ */
    $avatar_path = $user['avatar_path'];

    if (!empty($_FILES['avatar']['name'])) {
        $file_name = time() . "_" . basename($_FILES['avatar']['name']);
        $target = "../assets/images/avatar" . $file_name;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
            $avatar_path = "assets/images/avatar" . $file_name;
        }
    }

    /* -----------------------------------
       ⚡ 2B. Update Users Table
    ------------------------------------ */
    $stmt1 = $pdo->prepare("
        UPDATE seafood_users
        SET user_name = ?, email = ?, avatar_path = ?
        WHERE user_code = ?
    ");
    $stmt1->execute([$username, $email, $avatar_path, $user_code]);

    /* -----------------------------------
       ⚡ 2C. Update Profile Table
    ------------------------------------ */
    $stmt2 = $pdo->prepare("
        UPDATE seafood_user_profiles
        SET full_name = ?, phone_no = ?, dob = ?, address = ?,
            receive_updates = ?, receive_promotions = ?
        WHERE user_code = ?
    ");

    $stmt2->execute([
        $full_name, $phone, $dob, $address,
        $updates, $promotions,
        $user_code
    ]);

    // Update SESSION
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['avatar'] = $avatar_path;

    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: profile.php");
    exit();
}

/* ---------------------------------------------------------
   PAGE SETTINGS
---------------------------------------------------------- */
$page_title = "My Profile";
$show_breadcrumb = true;
$breadcrumb_items = [
    ["url" => "dashboard.php", "title" => "Dashboard"],
    ["url" => "#", "title" => "Profile Settings"]
];

include "../includes/header.php";
?>

<section class="profile-section">
    <div class="container">
        <h1>👤 My Profile</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="profile-form">

            <!-- Avatar -->
            <div class="avatar-box">
                <img src="../<?= $user['avatar_path'] ?: 'assets/default-avatar.png' ?>" class="avatar-preview">
                <input type="file" name="avatar" accept="image/*">
            </div>

            <h3>Account Information</h3>

            <label>Username</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($user['user_name']) ?>">

            <label>Email</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">

            <h3>Personal Details</h3>

            <label>Full Name</label>
            <input type="text" name="full_name" required value="<?= htmlspecialchars($user['full_name']) ?>">

            <label>Phone Number</label>
            <input type="text" name="phone_no" value="<?= htmlspecialchars($user['phone_no']) ?>">

            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($user['dob']) ?>">

            <label>Address</label>
            <textarea name="address" rows="3"><?= htmlspecialchars($user['address']) ?></textarea>

            <h3>Preferences</h3>

            <label>
                <input type="checkbox" name="receive_updates" <?= $user['receive_updates'] ? "checked" : "" ?>>
                Receive Order Updates
            </label>

            <label>
                <input type="checkbox" name="receive_promotions" <?= $user['receive_promotions'] ? "checked" : "" ?>>
                Receive Promotions
            </label>

            <button class="btn btn-primary" style="margin-top:15px;">Save Changes</button>
        </form>
    </div>
</section>

<style>
.profile-section { padding: 40px 0; }
.profile-form { max-width: 600px; margin: auto; display:flex; flex-direction:column; gap:15px; }
.avatar-box { text-align:center; margin-bottom:20px; }
.avatar-preview { width:120px; height:120px; border-radius:50%; object-fit:cover; margin-bottom:10px; }
.profile-form input, textarea { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
.profile-form h3 { margin-top:20px; }
.btn-primary { padding: 10px 20px; }
</style>

<?php include "../includes/footer.php"; ?>
