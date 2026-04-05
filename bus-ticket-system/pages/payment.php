<?php
// pages/payment.php - Complete Payment Page
require_once '../includes/config.php';

if(!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

if(!$booking_id) {
    $_SESSION['error'] = 'No booking ID provided';
    header("Location: my-bookings.php");
    exit();
}

// Get booking details
$user_id = $_SESSION['user_id'];
$query = "SELECT b.*, u.name, u.email, u.phone, r.from_city, r.to_city, r.bus_name, r.bus_type, 
          r.departure_time, r.arrival_time, r.fare 
          FROM bookings b 
          JOIN users u ON b.user_id = u.user_id 
          JOIN routes r ON b.route_id = r.id 
          WHERE b.booking_id = '$booking_id' AND b.user_id = '$user_id'";

$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if(!$booking) {
    $_SESSION['error'] = 'Booking not found';
    header("Location: my-bookings.php");
    exit();
}

$seat_count = count(explode(',', $booking['seat_numbers']));
$service_charge = 20 * $seat_count;
$grand_total = $booking['total_fare'] + $service_charge;

// Process payment
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    
    $update_query = "UPDATE bookings SET 
                     payment_status = 'completed', 
                     status = 'confirmed',
                     payment_method = '$payment_method',
                     payment_date = NOW()
                     WHERE booking_id = '$booking_id'";
    
    if(mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = 'Payment successful! Your ticket is now available.';
        header("Location: my-bookings.php");
        exit();
    } else {
        $error = 'Payment failed: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .payment-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .payment-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .payment-body {
            padding: 30px;
        }
        
        .booking-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        
        .info-label {
            width: 120px;
            font-weight: 600;
            color: #555;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .amount-box {
            text-align: center;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: var(--dark-green);
        }
        
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        
        .payment-method {
            text-align: center;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method.selected {
            border-color: var(--primary-green);
            background: #e8f5e9;
        }
        
        .payment-method img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-bottom: 8px;
        }
        
        .btn-pay {
            width: 100%;
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-pay:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
        }
        
        .deadline {
            background: #fff3cd;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            color: #856404;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="payment-container">
            <div class="payment-header">
                <h2><i class="fas fa-credit-card"></i> Complete Payment</h2>
                <p>Booking ID: <?php echo $booking['booking_id']; ?></p>
            </div>
            
            <div class="payment-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="booking-summary">
                    <h4 style="color: var(--dark-green); margin-bottom: 15px;">Booking Summary</h4>
                    <div class="info-row">
                        <div class="info-label">Route:</div>
                        <div class="info-value"><?php echo $booking['from_city']; ?> → <?php echo $booking['to_city']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Bus:</div>
                        <div class="info-value"><?php echo $booking['bus_name']; ?> (<?php echo $booking['bus_type']; ?>)</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Journey Date:</div>
                        <div class="info-value"><?php echo date('d M, Y', strtotime($booking['journey_date'])); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Seat Nos:</div>
                        <div class="info-value"><strong><?php echo $booking['seat_numbers']; ?></strong></div>
                    </div>
                </div>
                
                <div class="amount-box">
                    <small>Total Amount to Pay</small>
                    <div class="amount">BDT <?php echo number_format($grand_total); ?></div>
                    <small>(Including service charge)</small>
                </div>
                
                <?php if($booking['payment_deadline']): ?>
                <div class="deadline">
                    <i class="fas fa-clock"></i> 
                    Complete payment by: <?php echo date('d M, Y h:i A', strtotime($booking['payment_deadline'])); ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="paymentForm">
                    <h4 style="margin-bottom: 15px;">Select Payment Method</h4>
                    
                    <div class="payment-methods">
                        <div class="payment-method" onclick="selectMethod('bkash')">
                            <img src="../assets/images/bkash-logo.png" alt="bKash">
                            <div><strong>bKash</strong></div>
                        </div>
                        <div class="payment-method" onclick="selectMethod('nagad')">
                            <img src="../assets/images/nagad-logo.png" alt="Nagad">
                            <div><strong>Nagad</strong></div>
                        </div>
                        <div class="payment-method" onclick="selectMethod('card')">
                            <i class="fas fa-credit-card" style="font-size: 40px; color: #3498db;"></i>
                            <div><strong>Credit Card</strong></div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="payment_method" id="payment_method" required>
                    
                    <button type="submit" class="btn-pay" id="payBtn" disabled>
                        <i class="fas fa-lock"></i> Pay BDT <?php echo number_format($grand_total); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        let selectedMethod = null;
        
        function selectMethod(method) {
            selectedMethod = method;
            document.getElementById('payment_method').value = method;
            
            // Remove selected class from all
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selected class to clicked
            event.currentTarget.classList.add('selected');
            
            // Enable pay button
            document.getElementById('payBtn').disabled = false;
        }
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>