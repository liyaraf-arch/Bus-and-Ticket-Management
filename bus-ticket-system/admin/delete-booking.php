<?php
// admin/delete-booking.php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

if(!$booking_id) {
    echo json_encode(['success' => false, 'message' => 'No booking ID provided']);
    exit();
}

// Get booking id for response
$id_query = "SELECT id FROM bookings WHERE booking_id = '$booking_id'";
$id_result = mysqli_query($conn, $id_query);
$booking = mysqli_fetch_assoc($id_result);
$booking_db_id = $booking['id'] ?? 0;

// Delete booking
$query = "DELETE FROM bookings WHERE booking_id = '$booking_id'";

if(mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => 'Booking deleted successfully', 'id' => $booking_db_id]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>