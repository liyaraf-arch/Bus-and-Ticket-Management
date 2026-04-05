<?php
// process-payment.php
require_once 'includes/config.php';

header('Content-Type: application/json');

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

// Check if booking_id is provided
if(!isset($_POST['booking_id']) || empty($_POST['booking_id'])) {
    echo json_encode(['success' => false, 'message' => 'Booking ID is required']);
    exit();
}

$booking_id = sanitizeInput($_POST['booking_id']);
$user_id = $_SESSION['user_id'];

// First, check if booking exists
$check_query = "SELECT * FROM bookings WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
$check_result = mysqli_query($conn, $check_query);

if(mysqli_num_rows($check_result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
    exit();
}

// Update booking status to confirmed
$query = "UPDATE bookings SET payment_status = 'completed', status = 'confirmed' WHERE booking_id = '$booking_id' AND user_id = '$user_id'";

if(mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => 'Payment successful']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}
?>