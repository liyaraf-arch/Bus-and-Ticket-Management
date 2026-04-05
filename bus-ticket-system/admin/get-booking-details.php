<?php
// admin/get-booking-details.php
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

$query = "SELECT b.*, u.name as passenger_name, u.email, u.phone, 
          r.from_city, r.to_city, r.bus_name, r.bus_type, 
          r.departure_time, r.arrival_time 
          FROM bookings b 
          JOIN users u ON b.user_id = u.user_id 
          JOIN routes r ON b.route_id = r.id 
          WHERE b.booking_id = '$booking_id'";

$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if(!$booking) {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
    exit();
}

// Calculate service charge and grand total
$seat_count = count(explode(',', $booking['seat_numbers']));
$service_charge = 20 * $seat_count;
$grand_total = $booking['total_fare'] + $service_charge;

echo json_encode([
    'success' => true,
    'booking' => [
        'id' => $booking['id'],
        'booking_id' => $booking['booking_id'],
        'status' => $booking['status'],
        'booking_date' => date('d M, Y h:i A', strtotime($booking['booking_date'])),
        'passenger_name' => $booking['passenger_name'],
        'email' => $booking['email'],
        'phone' => $booking['phone'],
        'from_city' => $booking['from_city'],
        'to_city' => $booking['to_city'],
        'bus_name' => $booking['bus_name'],
        'bus_type' => $booking['bus_type'],
        'journey_date' => date('d M, Y', strtotime($booking['journey_date'])),
        'departure_time' => date('h:i A', strtotime($booking['departure_time'])),
        'arrival_time' => date('h:i A', strtotime($booking['arrival_time'])),
        'seat_numbers' => $booking['seat_numbers'],
        'total_fare' => number_format($booking['total_fare']),
        'service_charge' => number_format($service_charge),
        'grand_total' => number_format($grand_total),
        'payment_method' => $booking['payment_method'] ?? 'pending',
        'payment_status' => $booking['payment_status'] ?? 'pending'
    ]
]);
?>