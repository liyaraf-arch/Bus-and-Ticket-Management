<?php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$user_id = sanitizeInput($_GET['user_id']);

// Don't delete current admin
if($user_id == $_SESSION['admin_id']) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
    exit();
}

$query = "DELETE FROM users WHERE user_id = '$user_id'";

if(mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>