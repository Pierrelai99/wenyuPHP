<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    handleLogin();
} elseif ($action === 'register') {
    handleRegister();
} else {
    header('Location: login.php');
    exit();
}

//
// ========================= LOGIN FUNCTION ===============================
//
function handleLogin() {
    global $pdo;

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields.';
        header('Location: login.php');
        exit();
    }

    try {
        // Fetch from seafood_users
        $stmt = $pdo->prepare("
            SELECT user_code, user_name, email, pwd_hash, user_role, avatar_path 
            FROM seafood_users 
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // verify password
        // verify password (allow plain text for testing)
if ($user && $password === $user['pwd_hash']) {


            // user session
            $_SESSION['user_code'] = $user['user_code'];
            $_SESSION['username']  = $user['user_name'];
            $_SESSION['email']     = $user['email'];
            $_SESSION['role']      = $user['user_role'];
            $_SESSION['avatar']    = $user['avatar_path'];

            // Remember token
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');

                $stmt = $pdo->prepare("UPDATE seafood_users SET remember_token = ? WHERE user_code = ?");
                $stmt->execute([$token, $user['user_code']]);
            }

            $_SESSION['success'] = "Welcome back, {$user['user_name']}!";

            // redirect
            if ($user['user_role'] === 'admin') {
                header('Location: ../admin/dashboard.php');
            } else {
                header('Location: ../member/dashboard.php');
            }
            exit();

        } else {
            $_SESSION['error'] = 'Invalid email or password.';
            header('Location: login.php');
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error occurred.';
        header('Location: login.php');
        exit();
    }
}


//
// ========================= REGISTER FUNCTION ===============================
//
function handleRegister() {
    global $pdo;

    $first = trim($_POST['first_name'] ?? '');
    $last  = trim($_POST['last_name'] ?? '');
    $full_name = $first . ' ' . $last;

    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $dob        = $_POST['date_of_birth'] ?? null;
    $address_line1 = trim($_POST['address_line1'] ?? '');
    $address_line2 = trim($_POST['address_line2'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $postal = trim($_POST['postal_code'] ?? '');
    $country = trim($_POST['country'] ?? '');

    $address = $address_line1;

    if (!empty($address_line2)) {
        $address .= ", " . $address_line2;
    }

    $address .= ", $city, $state, $postal, $country";

    $password   = $_POST['password'] ?? '';
    $confirm_pw = $_POST['confirm_password'] ?? '';
    $updates    = isset($_POST['updates']);
    $promo      = isset($_POST['promotions']);


    // validation
    if (empty($first) || empty($last) || empty($email) || empty($password) || empty($confirm_pw)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        $_SESSION['form_data'] = $_POST;
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        $_SESSION['form_data'] = $_POST;
        header("Location: register.php");
        exit();
    }

    if ($password !== $confirm_pw) {
        $_SESSION['error'] = "Passwords do not match.";
        $_SESSION['form_data'] = $_POST;
        header("Location: register.php");
        exit();
    }

    // Check duplicate email
    $check = $pdo->prepare("SELECT email FROM seafood_users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: register.php");
        exit();
    }

    try {
        // generate USR code
        $user_code = generateUserCode($pdo);

        // insert user
        $stmt = $pdo->prepare("
            INSERT INTO seafood_users (user_code, user_name, email, pwd_hash, user_role)
            VALUES (?, ?, ?, ?, 'customer')
        ");

        // username = email prefix
        $username = explode('@', $email)[0];

        $stmt->execute([
            $user_code,
            $username,
            $email,
            $password //password_hash($password, PASSWORD_DEFAULT)

        ]);

        // insert user profile
        $stmt2 = $pdo->prepare("
            INSERT INTO seafood_user_profiles 
                (user_code, full_name, phone_no, dob, address, receive_updates, receive_promotions)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt2->execute([
            $user_code,
            $full_name,
            $phone,
            $dob,
            $address,
            $updates ? 1 : 0,
            $promo ? 1 : 0
        ]);

        // auto-login
        $_SESSION['user_code'] = $user_code;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'customer';

        unset($_SESSION['form_data']);

        $_SESSION['success'] = "Registration successful! Welcome, $full_name.";
        header("Location: ../member/dashboard.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Registration error: " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
}

//
?>
