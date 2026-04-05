<?php
// admin/delete-bus-ajax.php - AJAX Delete Bus
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$bus_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($bus_id == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid bus ID']);
    exit();
}

// Check if bus has any bookings
$check_query = "SELECT COUNT(*) as count FROM bookings WHERE route_id = $bus_id";
$check_result = mysqli_query($conn, $check_query);
$check = mysqli_fetch_assoc($check_result);

if($check['count'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete: This bus has ' . $check['count'] . ' existing booking(s)']);
    exit();
}

// Delete the bus
$delete_query = "DELETE FROM routes WHERE id = $bus_id";

if(mysqli_query($conn, $delete_query)) {
    echo json_encode(['success' => true, 'message' => 'Bus deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>