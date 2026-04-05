<?php
require_once '../includes/config.php';

if(!isLoggedIn()) {
    $_SESSION['error'] = 'Please login to confirm booking';
    redirect('login.php');
}

$page_title = 'Booking Confirmation';

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('index.php');
}

$route_id = (int)$_POST['route_id'];
$journey_date = sanitizeInput($_POST['journey_date']);
$seat_numbers = sanitizeInput($_POST['seat_numbers']);
$total_fare = (float)$_POST['total_fare'];

// Validate data
if(!$route_id || !$journey_date || !$seat_numbers || !$total_fare) {
    $_SESSION['error'] = 'Invalid booking data';
    redirect('index.php');
}

// Get route details
$route_query = "SELECT * FROM routes WHERE id = $route_id";
$route_result = mysqli_query($conn, $route_query);
$route = mysqli_fetch_assoc($route_result);

if(!$route) {
    $_SESSION['error'] = 'Route not found';
    redirect('index.php');
}

// Check if seats are still available
$seats = getAvailableSeats($route_id, $journey_date);
$selected_seats_array = explode(',', $seat_numbers);
$booked_seats = $seats['booked'];

foreach($selected_seats_array as $seat) {
    if(in_array($seat, $booked_seats)) {
        $_SESSION['error'] = "Seat $seat is no longer available. Please select again.";
        redirect("seat-selection.php?route_id=$route_id&date=$journey_date");
    }
}

// Generate booking ID
$booking_id = generateBookingId();

// Insert booking with payment_status = pending
$user_id = $_SESSION['user_id'];
$query = "INSERT INTO bookings (booking_id, user_id, route_id, seat_numbers, journey_date, total_fare, status, payment_status) 
          VALUES ('$booking_id', '$user_id', $route_id, '$seat_numbers', '$journey_date', $total_fare, 'pending', 'pending')";

if(mysqli_query($conn, $query)) {
    // Get booking details for display
    $booking_query = "SELECT b.*, u.name, u.email, u.phone, r.from_city, r.to_city, r.bus_name, r.bus_type, r.departure_time, r.arrival_time 
                     FROM bookings b 
                     JOIN users u ON b.user_id = u.user_id 
                     JOIN routes r ON b.route_id = r.id 
                     WHERE b.booking_id = '$booking_id'";
    $booking_result = mysqli_query($conn, $booking_query);
    $booking = mysqli_fetch_assoc($booking_result);
} else {
    $_SESSION['error'] = 'Booking failed: ' . mysqli_error($conn);
    redirect("seat-selection.php?route_id=$route_id&date=$journey_date");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .ticket-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            margin: 30px auto;
        }
        
        .ticket-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .ticket-body {
            padding: 30px;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .info-label {
            width: 130px;
            font-weight: 600;
            color: #555;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .payment-section {
            background: linear-gradient(135deg, #f8f9fa, #fff);
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
            border: 2px solid var(--primary-green);
        }
        
        .payment-methods {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .payment-method {
            flex: 1;
            min-width: 120px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method.selected {
            border-color: var(--primary-green);
            background: #e8f5e9;
        }
        
        .payment-method i {
            font-size: 30px;
            margin-bottom: 10px;
        }
        
        .payment-method:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn-pay {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-pay:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
        }
        
        .btn-download {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        
        .payment-status {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .payment-status.pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .payment-status.completed {
            background: #d4edda;
            color: #155724;
        }
        
        .hidden {
            display: none;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            max-width: 400px;
            animation: slideIn 0.3s ease;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-green);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div style="max-width: 800px; margin: 30px auto;">
            
            <!-- Ticket Container -->
            <div class="ticket-container">
                
                <!-- Ticket Header -->
                <div class="ticket-header">
                    <h2 style="margin: 0; color: white;"><?php echo SITE_NAME; ?></h2>
                    <p style="margin: 5px 0 0;">Bus Ticket</p>
                    <div style="margin-top: 10px;">
                        <small>Booking ID: <strong><?php echo $booking['booking_id']; ?></strong></small>
                    </div>
                </div>
                
                <!-- Ticket Body -->
                <div class="ticket-body">
                    
                    <!-- Payment Status -->
                    <div id="paymentStatus" class="payment-status pending">
                        <i class="fas fa-clock"></i> 
                        Payment Pending. Please complete payment to confirm your booking.
                    </div>
                    
                    <!-- Passenger Info -->
                    <div class="info-section">
                        <h4 style="color: var(--dark-green); margin-bottom: 15px;">
                            <i class="fas fa-user"></i> Passenger Details
                        </h4>
                        <div class="info-row">
                            <div class="info-label">Full Name:</div>
                            <div class="info-value"><?php echo $booking['name']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email Address:</div>
                            <div class="info-value"><?php echo $booking['email']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phone Number:</div>
                            <div class="info-value"><?php echo $booking['phone']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Booking Date:</div>
                            <div class="info-value"><?php echo date('d M, Y h:i A', strtotime($booking['booking_date'])); ?></div>
                        </div>
                    </div>
                    
                    <!-- Journey Info -->
                    <div class="info-section">
                        <h4 style="color: var(--dark-green); margin-bottom: 15px;">
                            <i class="fas fa-bus"></i> Journey Details
                        </h4>
                        <div class="info-row">
                            <div class="info-label">Bus Name:</div>
                            <div class="info-value"><strong><?php echo $booking['bus_name']; ?></strong> (<?php echo $booking['bus_type']; ?>)</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Route:</div>
                            <div class="info-value"><?php echo $booking['from_city']; ?> → <?php echo $booking['to_city']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Journey Date:</div>
                            <div class="info-value"><?php echo date('d M, Y', strtotime($booking['journey_date'])); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Departure Time:</div>
                            <div class="info-value"><?php echo date('h:i A', strtotime($booking['departure_time'])); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Arrival Time:</div>
                            <div class="info-value"><?php echo date('h:i A', strtotime($booking['arrival_time'])); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Seat Numbers:</div>
                            <div class="info-value"><strong style="color: var(--dark-green);"><?php echo $booking['seat_numbers']; ?></strong></div>
                        </div>
                    </div>
                    
                    <!-- Payment Section -->
                    <div id="paymentSection" class="payment-section">
                        <h4 style="color: var(--dark-green); margin-bottom: 20px; text-align: center;">
                            <i class="fas fa-credit-card"></i> Complete Payment
                        </h4>
                        
                        <?php 
                        $seat_count = count(explode(',', $booking['seat_numbers']));
                        $service_charge = 20 * $seat_count;
                        $grand_total = $booking['total_fare'] + $service_charge;
                        ?>
                        
                        <div style="background: #e8f5e9; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Ticket Fare (<?php echo $seat_count; ?> seats):</span>
                                <span>BDT <?php echo number_format($booking['total_fare']); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Service Charge:</span>
                                <span>BDT <?php echo number_format($service_charge); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 18px; padding-top: 10px; border-top: 2px solid var(--primary-green);">
                                <span>Grand Total:</span>
                                <span style="color: var(--dark-green);">BDT <?php echo number_format($grand_total); ?></span>
                            </div>
                        </div>
                        
                        <div class="payment-methods">
                            <div class="payment-method" onclick="selectPaymentMethod('bkash')">
                                <img src="../assets/images/bkash-logo.png" 
             alt="bKash" 
             style="width: 55px; height: 55px; object-fit: contain; margin-bottom: 8px;">
                                <div><strong>bKash</strong></div>
                                <small>Merchant Payment</small>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod('nagad')">
                                <img src="../assets/images/nagad-logo.png" 
             alt="Nagad" 
             style="width: 55px; height: 55px; object-fit: contain; margin-bottom: 8px;">
                                <div><strong>Nagad</strong></div>
                                <small>Mobile Banking</small>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod('card')">
                                <img src="../assets/images/visa-master.png" 
             alt="Cards" 
             style="width: 55px; height: 55px; object-fit: contain; margin-bottom: 8px;">
                                <div><strong>Cards</strong></div>
                                <small>Visa/Mastercard</small>
                            </div>
                        </div>
                        
                        <input type="hidden" id="selectedMethod" value="">
                        <button class="btn-pay" onclick="processPayment(<?php echo $grand_total; ?>, '<?php echo $booking_id; ?>')">
                            <i class="fas fa-lock"></i> Pay BDT <?php echo number_format($grand_total); ?>
                        </button>
                    </div>
                    
                    <!-- Download Section (Hidden initially) -->
                    <div id="downloadSection" class="info-section" style="display: none; text-align: center;">
                        <h4 style="color: var(--dark-green); margin-bottom: 15px;">
                            <i class="fas fa-check-circle"></i> Payment Successful!
                        </h4>
                        <p>Your ticket has been confirmed. You can now download your ticket.</p>
                        <div style="margin-top: 20px;">
                            <a href="<?php echo SITE_URL; ?>download-ticket.php?booking_id=<?php echo $booking_id; ?>" 
                               class="btn-download" target="_blank">
                                <i class="fas fa-download"></i> Download Ticket PDF
                            </a>
                            <a href="my-bookings.php" 
           class="btn-download" 
           style="background: #3498db; cursor: pointer;"
           onclick="return goToMyBookings();">
            <i class="fas fa-list"></i> View My Bookings
        </a>
                            <a href="../index.php" class="btn-download" style="background: #666;">
                                <i class="fas fa-home"></i> Go to Home
                            </a>
                        </div>
                    </div>
                    
                    <!-- Important Notes -->
                    <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <strong><i class="fas fa-info-circle"></i> Important Notes:</strong>
                        <ul style="margin: 10px 0 0 20px;">
                            <li>Please arrive at least 30 minutes before departure</li>
                            <li>Carry a printout or digital copy of this ticket</li>
                            <li>Valid ID proof is mandatory for boarding</li>
                            <li>Cancellation is available up to 2 hours before departure</li>
                        </ul>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <div class="spinner"></div>
            <h3>Processing Payment...</h3>
            <p>Please wait while we process your payment.</p>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <i class="fas fa-check-circle" style="font-size: 60px; color: #27ae60;"></i>
            <h3 style="color: #27ae60; margin: 15px 0;">Payment Successful!</h3>
            <p>Your booking has been confirmed.</p>
            <button onclick="closeSuccessModal()" class="btn-pay" style="margin-top: 20px;">OK</button>
        </div>
    </div>
    
    <script>
    let selectedPaymentMethod = null;
    
    
    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;
        document.getElementById('selectedMethod').value = method;
        
        // Remove selected class from all
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Add selected class to clicked
        event.currentTarget.classList.add('selected');
    }
    
    function processPayment(amount, bookingId) {
        if(!selectedPaymentMethod) {
            alert('Please select a payment method first!');
            return;
        }
        
        // Show payment modal
        const modal = document.getElementById('paymentModal');
        modal.style.display = 'flex';
        
        // Simulate payment processing (Demo)
        setTimeout(() => {
            // Close payment modal
            modal.style.display = 'none';
            
            // Send request to process payment
            fetch('../process-payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'booking_id=' + encodeURIComponent(bookingId)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Payment response:', data);
                
                if(data.success) {
                    // Show success modal
                    showSuccessModal();
                    
                    // Update UI
                    document.getElementById('paymentSection').style.display = 'none';
                    document.getElementById('downloadSection').style.display = 'block';
                    document.getElementById('paymentStatus').className = 'payment-status completed';
                    document.getElementById('paymentStatus').innerHTML = '<i class="fas fa-check-circle"></i> Payment Completed! Your ticket is confirmed.';
                    
                    // Optional: Update booking status in background
                    updateBookingStatus(bookingId);
                } else {
                    alert('Payment failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Payment processing failed. Please try again. Error: ' + error.message);
            });
        }, 1500);
    }
    
    function showSuccessModal() {
        const modal = document.getElementById('successModal');
        modal.style.display = 'flex';
        
        // Auto close after 2 seconds
        setTimeout(() => {
            closeSuccessModal();
        }, 2000);
    }
    
    function closeSuccessModal() {
        document.getElementById('successModal').style.display = 'none';
        // Scroll to download section
        document.getElementById('downloadSection').scrollIntoView({ behavior: 'smooth' });
    }
    
    function updateBookingStatus(bookingId) {
        // Optional: Update booking status without page reload
        fetch('../update-booking-status.php?booking_id=' + bookingId)
            .catch(error => console.log('Status update error:', error));
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const paymentModal = document.getElementById('paymentModal');
        const successModal = document.getElementById('successModal');
        
        if(event.target === paymentModal) {
            paymentModal.style.display = 'none';
        }
        if(event.target === successModal) {
            successModal.style.display = 'none';
        }
    }
    
    // Debug function to test payment
    function testPayment(bookingId) {
        console.log('Testing payment for booking:', bookingId);
        
        fetch('../process-payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'booking_id=' + encodeURIComponent(bookingId)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Test response:', data);
            if(data.success) {
                alert('Payment test successful!');
            } else {
                alert('Payment test failed: ' + data.message);
            }
        });
    }
</script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>