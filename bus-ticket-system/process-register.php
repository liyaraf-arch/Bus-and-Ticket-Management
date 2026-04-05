<?php
// process-register.php
require_once 'includes/config.php';

// No JSON response, redirect with session message
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

$name = sanitizeInput($_POST['name']);
$email = sanitizeInput($_POST['email']);
$phone = sanitizeInput($_POST['phone']);
$password = $_POST['password'];

// Validation
if(empty($name) || empty($email) || empty($phone) || empty($password)) {
    $_SESSION['error'] = 'All fields are required';
    header("Location: pages/register.php");
    exit();
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email format';
    header("Location: pages/register.php");
    exit();
}

if(!preg_match('/^01[3-9]\d{8}$/', $phone)) {
    $_SESSION['error'] = 'Invalid Bangladeshi phone number';
    header("Location: pages/register.php");
    exit();
}

if(strlen($password) < 6) {
    $_SESSION['error'] = 'Password must be at least 6 characters';
    header("Location: pages/register.php");
    exit();
}

// Check if email exists
$check_email = "SELECT id FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $check_email);

if(mysqli_num_rows($result) > 0) {
    $_SESSION['error'] = 'Email already exists';
    header("Location: pages/register.php");
    exit();
}

// Check if phone exists
$check_phone = "SELECT id FROM users WHERE phone = '$phone'";
$result = mysqli_query($conn, $check_phone);

if(mysqli_num_rows($result) > 0) {
    $_SESSION['error'] = 'Phone number already exists';
    header("Location: pages/register.php");
    exit();
}

// Generate unique user ID
$user_id = generateUserId();

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$query = "INSERT INTO users (user_id, name, email, phone, password) 
          VALUES ('$user_id', '$name', '$email', '$phone', '$hashed_password')";

if(mysqli_query($conn, $query)) {
    // Set success message
    $_SESSION['success'] = '✓ Registration successful! Welcome to ' . SITE_NAME . '! Please login to continue.';
    header("Location: pages/login.php");
    exit();
} else {
    $_SESSION['error'] = 'Registration failed: ' . mysqli_error($conn);
    header("Location: pages/register.php");
    exit();
}
?>