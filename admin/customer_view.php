<?php
session_start();

// Admin access check
if (!isset($_SESSION['user_code']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

require_once '../includes/db.php';

// ---------------------------------------------
// LOAD USER DATA
// ---------------------------------------------
if (!isset($_GET['code'])) {
    header("Location: members.php");
    exit();
}

$user_code = $_GET['code'];

// Load user & profile
$sql = "SELECT 
            u.user_code,
            u.user_name,
            u.email,
            u.user_role,
            u.avatar_path,
            u.created_on,
            u.updated_on,
            p.profile_id,
            p.full_name,
            p.phone_no,
            p.dob,
            p.address,
            p.receive_updates,
            p.receive_promotions
        FROM seafood_users u
        LEFT JOIN seafood_user_profiles p ON u.user_code = p.user_code
        WHERE u.user_code = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_code]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "User not found!";
    header("Location: members.php");
    exit();
}


// ---------------------------------------------
// HANDLE UPDATE FORM
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['user_name'];
    $email = $_POST['email'];
    $role = $_POST['user_role'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone_no'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $updates = isset($_POST['receive_updates']) ? 1 : 0;
    $promotions = isset($_POST['receive_promotions']) ? 1 : 0;

    // -------- Handle Avatar Upload --------
    $avatar_path = $user['avatar_path'];

    if (!empty($_FILES['avatar']['name'])) {
        $targetDir = "../uploads/avatars/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $filename = $user_code . "_" . time() . ".jpg";
        $targetFile = $targetDir . $filename;

        move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile);

        // Store path relative for frontend
        $avatar_path = "uploads/avatars/" . $filename;
    }

    // Update seafood_users
    $stmt1 = $pdo->prepare("
        UPDATE seafood_users 
        SET user_name=?, email=?, user_role=?, avatar_path=? 
        WHERE user_code=?
    ");
    $stmt1->execute([$username, $email, $role, $avatar_path, $user_code]);

    // Update seafood_user_profiles
    // (Insert if profile not exist)
    if ($user['profile_id']) {
        $stmt2 = $pdo->prepare("
            UPDATE seafood_user_profiles 
            SET full_name=?, phone_no=?, dob=?, address=?, receive_updates=?, receive_promotions=? 
            WHERE user_code=?
        ");
        $stmt2->execute([$full_name, $phone, $dob, $address, $updates, $promotions, $user_code]);

    } else {
        $stmt2 = $pdo->prepare("
            INSERT INTO seafood_user_profiles (user_code, full_name, phone_no, dob, address, receive_updates, receive_promotions)
            VALUES (?,?,?,?,?,?,?)
        ");
        $stmt2->execute([$user_code, $full_name, $phone, $dob, $address, $updates, $promotions]);
    }

    $_SESSION['success'] = "Customer details updated successfully!";
    header("Location: customer_view.php?code=" . $user_code);
    exit();
}

$page_title = "Customer Details";
include '../includes/header.php';
?>


<!-- PAGE UI -->
<section class="admin-section">
    <div class="container">

        <h2>Customer Details</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>


        <form method="POST" enctype="multipart/form-data" 
              style="background:#fff; padding:25px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">

            <!-- Avatar -->
            <div style="display:flex; gap:20px; align-items:center; margin-bottom:20px;">
                <img src="/<?= $user['avatar_path'] ?: 'assets/images/default-avatar.png' ?>" 
                     style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:3px solid #ddd;">
                
                <div>
                    <label>Change Profile Photo</label><br>
                    <input type="file" name="avatar">
                </div>
            </div>


            <!-- Basic Info -->
            <h3>Account Information</h3>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap:15px;">

                <div>
                    <label>Username</label>
                    <input type="text" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>" required>
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div>
                    <label>User Role</label>
                    <select name="user_role">
                        <option value="customer" <?= $user['user_role']=='customer'?'selected':'' ?>>Customer</option>
                        <option value="admin" <?= $user['user_role']=='admin'?'selected':'' ?>>Admin</option>
                    </select>
                </div>

            </div>


            <!-- Profile Info -->
            <h3 style="margin-top:25px;">Profile Information</h3>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap:15px;">

                <div>
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>">
                </div>

                <div>
                    <label>Phone Number</label>
                    <input type="text" name="phone_no" value="<?= htmlspecialchars($user['phone_no']) ?>">
                </div>

                <div>
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="<?= $user['dob'] ?>">
                </div>
            </div>

            <div style="margin-top:15px;">
                <label>Address</label>
                <textarea name="address" rows="3"><?= htmlspecialchars($user['address']) ?></textarea>
            </div>


            <!-- Preferences -->
            <h3 style="margin-top:25px;">Preferences</h3>

            <label><input type="checkbox" name="receive_updates" <?= $user['receive_updates'] ? 'checked':'' ?>> Receive Updates</label><br>
            <label><input type="checkbox" name="receive_promotions" <?= $user['receive_promotions'] ? 'checked':'' ?>> Receive Promotions</label>


            <!-- Submit -->
            <div style="margin-top:30px;">
                <button class="btn btn-primary" type="submit">Save Changes</button>
                <a href="members.php" class="btn btn-secondary">Back</a>
            </div>

        </form>

    </div>
</section>


<?php include '../includes/footer.php'; ?>
