<?php
// process-login.php
require_once 'includes/config.php';

// No JSON response, redirect with session message
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

$email = sanitizeInput($_POST['email']);
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? true : false;

// Get redirect parameters
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '';
$route_id = isset($_POST['route_id']) ? $_POST['route_id'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';

if(empty($email) || empty($password)) {
    $_SESSION['error'] = 'Email and password are required';
    header("Location: pages/login.php");
    exit();
}

// Get user from database
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = 'Invalid email or password';
    header("Location: pages/login.php");
    exit();
}

$user = mysqli_fetch_assoc($result);

// Verify password
if(password_verify($password, $user['password'])) {
    // Set session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_phone'] = $user['phone'];
    $_SESSION['logged_in'] = true;
    
    // Set remember me cookie if requested
    if($remember) {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60); // 30 days
        setcookie('remember_token', $token, $expiry, '/');
        
        // Store token in database (optional)
        // $update_token = "UPDATE users SET remember_token = '$token' WHERE user_id = '{$user['user_id']}'";
        // mysqli_query($conn, $update_token);
    }
    
    // Log login activity
    $log_query = "INSERT INTO user_logs (user_id, action, ip_address) VALUES ('{$user['user_id']}', 'login', '{$_SERVER['REMOTE_ADDR']}')";
    mysqli_query($conn, $log_query);
    
    // Redirect based on where user came from
    if($redirect == 'seat-selection' && $route_id && $date) {
        header("Location: pages/seat-selection.php?route_id=$route_id&date=$date");
        exit();
    } else {
        $_SESSION['success'] = 'Welcome back, ' . $user['name'] . '!';
        header("Location: index.php");
        exit();
    }
    
} else {
    $_SESSION['error'] = 'Invalid email or password';
    header("Location: pages/login.php");
    exit();
}
?>