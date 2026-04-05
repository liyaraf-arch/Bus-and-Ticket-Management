<?php
// admin/update-bus.php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$id = (int)$_POST['bus_id'];
$from_city = sanitizeInput($_POST['from_city']);
$to_city = sanitizeInput($_POST['to_city']);
$bus_name = sanitizeInput($_POST['bus_name']);
$bus_type = sanitizeInput($_POST['bus_type']);
$departure_time = sanitizeInput($_POST['departure_time']);
$arrival_time = sanitizeInput($_POST['arrival_time']);
$fare = (float)$_POST['fare'];

$query = "UPDATE routes SET 
          from_city = '$from_city',
          to_city = '$to_city',
          bus_name = '$bus_name',
          bus_type = '$bus_type',
          departure_time = '$departure_time',
          arrival_time = '$arrival_time',
          fare = $fare
          WHERE id = $id";

if(mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => 'Bus updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>