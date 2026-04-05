<?php
// process-paylater.php
require_once 'includes/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$booking_id = sanitizeInput($_POST['booking_id']);
$payment_method = sanitizeInput($_POST['payment_method']);
$user_id = $_SESSION['user_id'];

// Check if booking exists
$check_query = "SELECT * FROM bookings WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
$check_result = mysqli_query($conn, $check_query);

if(mysqli_num_rows($check_result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
    exit();
}

// Set payment deadline to 4 hours from now
$deadline = date('Y-m-d H:i:s', strtotime('+4 hours'));

// Update booking with pay later status
$update_query = "UPDATE bookings SET 
                 payment_status = 'pay_later',
                 payment_method = '$payment_method',
                 payment_deadline = '$deadline',
                 status = 'pending'
                 WHERE booking_id = '$booking_id' AND user_id = '$user_id'";

if(mysqli_query($conn, $update_query)) {
    echo json_encode([
        'success' => true,
        'message' => 'Booking saved. You have 4 hours to complete payment.',
        'deadline' => date('d M, Y h:i A', strtotime($deadline))
    ]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>