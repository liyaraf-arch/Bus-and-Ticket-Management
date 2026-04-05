<?php
require_once 'includes/config.php';

header('Content-Type: application/json');

if(!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login to book tickets']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$route_id = (int)$_POST['route_id'];
$journey_date = sanitizeInput($_POST['journey_date']);
$seat_numbers = sanitizeInput($_POST['seat_numbers']);
$total_fare = (float)$_POST['total_fare'];
$passenger_name = sanitizeInput($_POST['passenger_name'] ?? '');
$passenger_age = (int)($_POST['passenger_age'] ?? 0);
$passenger_gender = sanitizeInput($_POST['passenger_gender'] ?? '');
$emergency_contact = sanitizeInput($_POST['emergency_contact'] ?? '');

// Validation
if(!$route_id || !$journey_date || !$seat_numbers || !$total_fare) {
    echo json_encode(['success' => false, 'message' => 'Missing booking information']);
    exit();
}

// Validate date
if(strtotime($journey_date) < strtotime(date('Y-m-d'))) {
    echo json_encode(['success' => false, 'message' => 'Invalid journey date']);
    exit();
}

// Check if seats are still available
$seats = getAvailableSeats($route_id, $journey_date);
$selected_seats_array = explode(',', $seat_numbers);
$booked_seats = $seats['booked'];

foreach($selected_seats_array as $seat) {
    if(in_array($seat, $booked_seats)) {
        echo json_encode(['success' => false, 'message' => "Seat $seat is no longer available"]);
        exit();
    }
}

// Begin transaction
mysqli_begin_transaction($conn);

try {
    // Generate booking ID
    $booking_id = generateBookingId();
    
    // Insert booking
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO bookings (booking_id, user_id, route_id, seat_numbers, journey_date, total_fare, status) 
              VALUES ('$booking_id', '$user_id', $route_id, '$seat_numbers', '$journey_date', $total_fare, 'confirmed')";
    
    if(!mysqli_query($conn, $query)) {
        throw new Exception('Failed to create booking');
    }
    
    // Insert passenger details if provided
    if(!empty($passenger_name)) {
        $passenger_query = "INSERT INTO passengers (booking_id, name, age, gender, emergency_contact) 
                           VALUES ('$booking_id', '$passenger_name', $passenger_age, '$passenger_gender', '$emergency_contact')";
        mysqli_query($conn, $passenger_query);
    }
    
    // Update seat availability (you might have a separate seats table)
    // UpdateSeatAvailability($route_id, $journey_date, $selected_seats_array);
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Send confirmation email (optional)
    // sendBookingConfirmation($user_id, $booking_id);
    
    echo json_encode([
        'success' => true,
        'message' => 'Booking confirmed successfully',
        'booking_id' => $booking_id,
        'redirect' => "pages/booking-confirmation.php?booking_id=$booking_id"
    ]);
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>