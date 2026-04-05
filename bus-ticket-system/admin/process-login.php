<?php
// admin/process-login.php

// session_start() চেক করুন
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/config.php';

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: login.php");
    exit();
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

// Get admin from database
$query = "SELECT * FROM admins WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    $_SESSION['admin_error'] = 'Invalid username or password';
    header("Location: login.php");
    exit();
}

$admin = mysqli_fetch_assoc($result);

// Verify password
if(password_verify($password, $admin['password'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_role'] = $admin['role'];
    
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['admin_error'] = 'Invalid username or password';
    header("Location: login.php");
    exit();
}
?>