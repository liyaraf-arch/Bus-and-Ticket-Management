<?php
// admin/save-bus.php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Validation
if(empty($_POST['from_city']) || empty($_POST['to_city']) || empty($_POST['bus_name'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

$from_city = sanitizeInput($_POST['from_city']);
$to_city = sanitizeInput($_POST['to_city']);
$bus_name = sanitizeInput($_POST['bus_name']);
$bus_type = sanitizeInput($_POST['bus_type']);
$departure_time = sanitizeInput($_POST['departure_time']);
$arrival_time = sanitizeInput($_POST['arrival_time']);
$fare = (float)$_POST['fare'];
$total_seats = (int)$_POST['total_seats'];

// Check if same city selected
if($from_city === $to_city) {
    echo json_encode(['success' => false, 'message' => 'From and To cities cannot be same']);
    exit();
}

$query = "INSERT INTO routes (from_city, to_city, bus_name, bus_type, departure_time, arrival_time, fare, total_seats) 
          VALUES ('$from_city', '$to_city', '$bus_name', '$bus_type', '$departure_time', '$arrival_time', $fare, $total_seats)";

if(mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => 'Bus added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>
<?php
// admin/save-bus.php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$trip_type = $_POST['trip_type'];
$from_city = sanitizeInput($_POST['from_city']);
$to_city = sanitizeInput($_POST['to_city']);
$bus_name = sanitizeInput($_POST['bus_name']);
$bus_type = sanitizeInput($_POST['bus_type']);
$departure_time = sanitizeInput($_POST['departure_time']);
$arrival_time = sanitizeInput($_POST['arrival_time']);
$fare = (float)$_POST['fare'];
$total_seats = (int)$_POST['total_seats'];
$round_trip_fare = isset($_POST['round_trip_fare']) && $_POST['round_trip_fare'] != '' ? (float)$_POST['round_trip_fare'] : ($fare * 1.8);

$query = "INSERT INTO routes (from_city, to_city, bus_name, bus_type, departure_time, arrival_time, fare, total_seats, is_round_trip, round_trip_fare) 
          VALUES ('$from_city', '$to_city', '$bus_name', '$bus_type', '$departure_time', '$arrival_time', $fare, $total_seats, 
                  " . ($trip_type == 'round' ? 1 : 0) . ", $round_trip_fare)";

if(mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => 'Bus added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>