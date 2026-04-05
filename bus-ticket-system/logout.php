<?php
require_once 'includes/config.php';

// Log logout activity if user was logged in
if(isset($_SESSION['user_id'])) {
    $log_query = "INSERT INTO user_logs (user_id, action, ip_address) VALUES ('{$_SESSION['user_id']}', 'logout', '{$_SERVER['REMOTE_ADDR']}')";
    mysqli_query($conn, $log_query);
}

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear remember me cookie if set
if(isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Redirect to home page
header("Location: index.php");
exit();
?>