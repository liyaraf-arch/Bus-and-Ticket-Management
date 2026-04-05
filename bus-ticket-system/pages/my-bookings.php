<?php
require_once '../includes/config.php';

if(!isLoggedIn()) {
    $_SESSION['error'] = 'Please login first';
    redirect('login.php');
}

$page_title = 'My Bookings';
$user_id = $_SESSION['user_id'];

// Handle cancellation
if(isset($_GET['cancel_id'])) {
    $cancel_id = sanitizeInput($_GET['cancel_id']);
    
    $check_query = "SELECT * FROM bookings WHERE booking_id = '$cancel_id' AND user_id = '$user_id' AND journey_date > CURDATE()";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        $update_query = "UPDATE bookings SET status = 'cancelled' WHERE booking_id = '$cancel_id'";
        if(mysqli_query($conn, $update_query)) {
            $_SESSION['success'] = 'Booking cancelled successfully!';
        } else {
            $_SESSION['error'] = 'Cancellation failed!';
        }
    } else {
        $_SESSION['error'] = 'Cannot cancel this booking';
    }
    redirect('my-bookings.php');
}

// Get user bookings
$query = "SELECT b.*, r.from_city, r.to_city, r.bus_name, r.bus_type, r.departure_time, r.arrival_time 
          FROM bookings b 
          JOIN routes r ON b.route_id = r.id 
          WHERE b.user_id = '$user_id' 
          ORDER BY b.booking_date DESC, b.id DESC";  // ← এখানে পরিবর্তন করুন
$result = mysqli_query($conn, $query);
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
        .bookings-container {
            max-width: 1200px;
            margin: 40px auto;
        }
        
        .booking-card {
            background: white;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .booking-card:hover {
            transform: translateY(-3px);
        }
        
        .booking-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .booking-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-confirmed {
            background: #28a745;
            color: white;
        }
        
        .status-cancelled {
            background: #dc3545;
            color: white;
        }
        
        .status-completed {
            background: #6c757d;
            color: white;
        }
        
        .booking-body {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .booking-info {
            padding: 10px;
        }
        
        .booking-info h4 {
            color: var(--dark-green);
            margin-bottom: 10px;
        }
        
        .booking-info p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }
        
        .booking-info i {
            width: 25px;
            color: var(--primary-green);
        }
        
        .booking-actions {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .btn-cancel {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-view {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover, .btn-view:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .no-bookings {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 15px;
        }
        
        .no-bookings i {
            font-size: 60px;
            color: #ccc;
        }
        
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: var(--dark-green);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="bookings-container">
            <h2 style="color: var(--dark-green); margin-bottom: 20px;">
                <i class="fas fa-ticket-alt"></i> My Bookings
            </h2>
            
            <?php displayMessage(); ?>
            
            <?php
            $total_bookings = mysqli_num_rows($result);
            $confirmed = 0;
            $cancelled = 0;
            $upcoming = 0;
            
            mysqli_data_seek($result, 0);
            while($row = mysqli_fetch_assoc($result)) {
                if($row['status'] == 'confirmed') $confirmed++;
                if($row['status'] == 'cancelled') $cancelled++;
                if(strtotime($row['journey_date']) > time()) $upcoming++;
            }
            mysqli_data_seek($result, 0);
            ?>
            
            <div class="stats-summary">
                <div class="stat-box">
                    <i class="fas fa-ticket-alt" style="font-size: 30px; color: var(--primary-green);"></i>
                    <div class="stat-number"><?php echo $total_bookings; ?></div>
                    <p>Total Bookings</p>
                </div>
                <div class="stat-box">
                    <i class="fas fa-check-circle" style="font-size: 30px; color: #28a745;"></i>
                    <div class="stat-number"><?php echo $confirmed; ?></div>
                    <p>Confirmed</p>
                </div>
                <div class="stat-box">
                    <i class="fas fa-times-circle" style="font-size: 30px; color: #dc3545;"></i>
                    <div class="stat-number"><?php echo $cancelled; ?></div>
                    <p>Cancelled</p>
                </div>
                <div class="stat-box">
                    <i class="fas fa-calendar" style="font-size: 30px; color: #3498db;"></i>
                    <div class="stat-number"><?php echo $upcoming; ?></div>
                    <p>Upcoming</p>
                </div>
            </div>
            
            <?php if($total_bookings == 0): ?>
                <div class="no-bookings">
                    <i class="fas fa-ticket-alt"></i>
                    <h3>No bookings found</h3>
                    <p>You haven't booked any tickets yet.</p>
                    <a href="search.php" class="btn btn-green" style="margin-top: 20px;">
                        <i class="fas fa-search"></i> Book a Ticket
                    </a>
                </div>
            <?php else: ?>
                <?php while($booking = mysqli_fetch_assoc($result)): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <div>
                                <strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?>
                            </div>
                            <div>
                                <span class="booking-status status-<?php echo $booking['status']; ?>">
                                    <?php echo strtoupper($booking['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="booking-body">
                            <div class="booking-info">
                                <h4><i class="fas fa-bus"></i> Journey Details</h4>
                                <p><i class="fas fa-road"></i> <?php echo $booking['from_city']; ?> → <?php echo $booking['to_city']; ?></p>
                                <p><i class="fas fa-bus"></i> <?php echo $booking['bus_name']; ?> (<?php echo $booking['bus_type']; ?>)</p>
                                <p><i class="fas fa-calendar"></i> <?php echo date('d M, Y', strtotime($booking['journey_date'])); ?></p>
                                <p><i class="fas fa-clock"></i> <?php echo date('h:i A', strtotime($booking['departure_time'])); ?></p>
                            </div>
                            
                            <div class="booking-info">
                                <h4><i class="fas fa-chair"></i> Seat Details</h4>
                                <p><i class="fas fa-chair"></i> Seat Nos: <strong><?php echo $booking['seat_numbers']; ?></strong></p>
                                <p><i class="fas fa-tag"></i> Total Fare: BDT <?php echo number_format($booking['total_fare']); ?></p>
                                <p><i class="fas fa-calendar-alt"></i> Booked on: <?php echo date('d M, Y', strtotime($booking['booking_date'])); ?></p>
                            </div>
                        </div>
                        
                        <!-- pages/my-bookings.php - Booking Actions Section -->

<!-- pages/my-bookings.php - Booking Actions Section -->

<!-- pages/my-bookings.php - Booking Actions Section -->

<div class="booking-actions">
    <?php if($booking['payment_status'] == 'pending' || $booking['payment_status'] == 'pay_later'): ?>
        <!-- পেমেন্ট পেন্ডিং থাকলে Complete Payment বাটন -->
        <button class="btn-pay" onclick="showPaymentRequired('<?php echo $booking['booking_id']; ?>')">
            <i class="fas fa-credit-card"></i> Complete Payment
        </button>
    <?php elseif($booking['payment_status'] == 'completed'): ?>
        <!-- পেমেন্ট কমপ্লিট থাকলে Download Ticket বাটন -->
        <a href="../download-ticket.php?booking_id=<?php echo $booking['booking_id']; ?>" class="btn-view" target="_blank">
            <i class="fas fa-download"></i> Download Ticket
        </a>
    <?php endif; ?>
    
    <?php if($booking['status'] == 'confirmed' && strtotime($booking['journey_date']) > time()): ?>
        <button class="btn-cancel" onclick="cancelBooking('<?php echo $booking['booking_id']; ?>')">
            <i class="fas fa-times"></i> Cancel Booking
        </button>
    <?php endif; ?>
    
    <!-- View Details Button - Updated -->
    <button class="btn-details" onclick="viewBookingDetails('<?php echo $booking['booking_id']; ?>', '<?php echo $booking['payment_status']; ?>')">
        <i class="fas fa-eye"></i> View Details
    </button>
</div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- pages/my-bookings.php - Add this script at the end of file -->

<<!-- pages/my-bookings.php - ফাইলের শেষ অংশ -->

    <!-- ... বাকি HTML কন্টেন্ট ... -->
    
    <!-- JavaScript Section - এখানে বসান -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function viewBookingDetails(bookingId, paymentStatus) {
            // পেমেন্ট স্ট্যাটাস চেক করুন
            if(paymentStatus == 'pending' || paymentStatus == 'pay_later') {
                Swal.fire({
                    title: 'Payment Required!',
                    text: 'You haven\'t completed the payment for this booking yet. Please complete payment to view ticket details.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Complete Payment',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#27ae60',
                    cancelButtonColor: '#666'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `payment.php?booking_id=${bookingId}`;
                    }
                });
            } else {
                window.location.href = `booking-confirmation.php?booking_id=${bookingId}`;
            }
        }
        
        function showPaymentRequired(bookingId) {
            Swal.fire({
                title: 'Payment Required!',
                text: 'You haven\'t completed the payment for this booking yet. Please complete payment to download your ticket.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Complete Payment',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#27ae60',
                cancelButtonColor: '#666'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `payment.php?booking_id=${bookingId}`;
                }
            });
        }
        
        function cancelBooking(bookingId) {
            Swal.fire({
                title: 'Cancel Booking?',
                text: 'Are you sure you want to cancel this booking? This action cannot be undone.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Cancel',
                cancelButtonText: 'No, Keep',
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#666'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `my-bookings.php?cancel_id=${bookingId}`;
                }
            });
        }
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
    
    <script>
        function cancelBooking(bookingId) {
            if(confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                window.location.href = `my-bookings.php?cancel_id=${bookingId}`;
            }
        }
    </script>
    
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>