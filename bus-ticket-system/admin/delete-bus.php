<?php
// admin/delete-bus.php - Delete Bus Route
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get bus ID from URL
$bus_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($bus_id == 0) {
    $_SESSION['error'] = 'Invalid bus ID';
    header("Location: dashboard.php");
    exit();
}

// Check if bus has any bookings
$check_query = "SELECT COUNT(*) as count FROM bookings WHERE route_id = $bus_id";
$check_result = mysqli_query($conn, $check_query);
$check = mysqli_fetch_assoc($check_result);

if($check['count'] > 0) {
    $_SESSION['error'] = 'Cannot delete: This bus has ' . $check['count'] . ' existing booking(s)';
    header("Location: dashboard.php");
    exit();
}

// Get bus details for confirmation message
$bus_query = "SELECT * FROM routes WHERE id = $bus_id";
$bus_result = mysqli_query($conn, $bus_query);
$bus = mysqli_fetch_assoc($bus_result);

// Delete the bus
$delete_query = "DELETE FROM routes WHERE id = $bus_id";

if(mysqli_query($conn, $delete_query)) {
    $_SESSION['success'] = 'Bus "' . $bus['bus_name'] . '" (' . $bus['from_city'] . ' → ' . $bus['to_city'] . ') has been deleted successfully!';
} else {
    $_SESSION['error'] = 'Delete failed: ' . mysqli_error($conn);
}

header("Location: dashboard.php");
exit();
?>