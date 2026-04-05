<?php
// download-ticket.php
require_once 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    die('Unauthorized access. Please login first.');
}

$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

if(!$booking_id) {
    die('No booking ID provided.');
}

// Get booking details with payment status
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
    die('Booking not found.');
}

// CHECK PAYMENT STATUS - যদি পেমেন্ট না করা হয় তাহলে টিকেট দেখাবে না
if($booking['payment_status'] != 'completed') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Payment Required</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background: #f5f5f5;
            }
            .payment-required {
                text-align: center;
                background: white;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                max-width: 400px;
            }
            .payment-required i {
                font-size: 60px;
                color: #f39c12;
                margin-bottom: 20px;
            }
            .payment-required h2 {
                color: #e74c3c;
                margin-bottom: 15px;
            }
            .btn-pay {
                background: #27ae60;
                color: white;
                border: none;
                padding: 12px 30px;
                border-radius: 8px;
                cursor: pointer;
                margin-top: 20px;
                text-decoration: none;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div class="payment-required">
            <i class="fas fa-credit-card"></i>
            <h2>Payment Required</h2>
            <p>You haven't completed the payment for this booking yet.</p>
            <p>Please complete payment to download your ticket.</p>
            <a href="pages/payment.php?booking_id=<?php echo $booking_id; ?>" class="btn-pay">
                <i class="fas fa-credit-card"></i> Complete Payment
            </a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// পেমেন্ট completed হলে এখানে আসবে
$seat_count = count(explode(',', $booking['seat_numbers']));
$service_charge = 20 * $seat_count;
$grand_total = $booking['total_fare'] + $service_charge;

// ✅ ওয়াটারমার্ক দেখানোর জন্য চেক - এখানে কোড বসাতে হবে
$show_watermark = ($booking['payment_status'] == 'completed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Ticket - <?php echo $booking_id; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: A4;
            margin: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .ticket {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        /* Watermark Style */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            font-weight: bold;
            color: rgba(46, 204, 113, 0.2);
            white-space: nowrap;
            z-index: 10;
            pointer-events: none;
            font-family: Arial, sans-serif;
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .ticket-body {
            padding: 30px;
        }
        
        .info-section {
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .section-title {
            background: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 2px solid #2ecc71;
            font-weight: bold;
            color: #27ae60;
        }
        
        .section-content {
            padding: 15px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
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
        
        .qr-section {
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-code img {
            width: 120px;
            height: 120px;
        }
        
        .notes {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            z-index: 1000;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .print-btn {
                display: none;
            }
            .ticket {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn">
        🖨️ Save as PDF / Print
    </button>
    
    <div class="ticket">
        <!-- ✅ ওয়াটারমার্ক - এখানে বসানো হয়েছে -->
        <?php if($show_watermark): ?>
            <div class="watermark">PAID ✓</div>
        <?php endif; ?>
        
        <div class="ticket-header">
            <h1><?php echo SITE_NAME; ?></h1>
            <p>Bus Ticket</p>
            <div class="booking-id">
                Booking ID: <?php echo $booking['booking_id']; ?>
            </div>
        </div>
        
        <div class="ticket-body">
            <!-- Passenger Info -->
            <div class="info-section">
                <div class="section-title">📋 Passenger Details</div>
                <div class="section-content">
                    <div class="info-row">
                        <div class="info-label">Full Name:</div>
                        <div class="info-value"><?php echo $booking['name']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value"><?php echo $booking['email']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone:</div>
                        <div class="info-value"><?php echo $booking['phone']; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Journey Info -->
            <div class="info-section">
                <div class="section-title">🚌 Journey Details</div>
                <div class="section-content">
                    <div class="info-row">
                        <div class="info-label">Bus Name:</div>
                        <div class="info-value"><?php echo $booking['bus_name']; ?> (<?php echo $booking['bus_type']; ?>)</div>
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
                        <div class="info-label">Departure:</div>
                        <div class="info-value"><?php echo date('h:i A', strtotime($booking['departure_time'])); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Arrival:</div>
                        <div class="info-value"><?php echo date('h:i A', strtotime($booking['arrival_time'])); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Seat Numbers:</div>
                        <div class="info-value"><strong><?php echo $booking['seat_numbers']; ?></strong></div>
                    </div>
                </div>
            </div>
            
            <!-- Fare Details -->
            <div class="info-section">
                <div class="section-title">💰 Fare Details</div>
                <div class="section-content">
                    <div class="info-row">
                        <div class="info-label">Ticket Fare:</div>
                        <div class="info-value">BDT <?php echo number_format($booking['total_fare']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Service Charge:</div>
                        <div class="info-value">BDT <?php echo number_format($service_charge); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Grand Total:</div>
                        <div class="info-value"><strong>BDT <?php echo number_format($grand_total); ?></strong></div>
                    </div>
                </div>
            </div>
            
            <!-- QR Code -->
            <div class="qr-section">
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=<?php echo urlencode('Booking ID: ' . $booking['booking_id'] . ' | ' . $booking['name'] . ' | ' . $booking['from_city'] . ' to ' . $booking['to_city']); ?>" alt="QR Code">
                </div>
                <p>Scan QR code at boarding point</p>
            </div>
            
            <!-- Notes -->
            <div class="notes">
                <strong>📌 Important Notes:</strong>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>Please arrive 30 minutes before departure</li>
                    <li>Carry a printout or digital copy of this ticket</li>
                    <li>Valid ID proof is mandatory for boarding</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>