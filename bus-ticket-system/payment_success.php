<?php
// payment_success.php - Direct success page for testing
require_once 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: pages/login.php");
    exit();
}

$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

if($booking_id) {
    // Update payment status
    $query = "UPDATE bookings SET payment_status = 'completed', status = 'confirmed' WHERE booking_id = '$booking_id' AND user_id = '{$_SESSION['user_id']}'";
    mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .success-container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            max-width: 500px;
            margin: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideIn 0.5s ease;
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
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: #d4edda;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .success-icon i {
            font-size: 50px;
            color: #27ae60;
        }
        
        h2 {
            color: #27ae60;
            margin-bottom: 10px;
        }
        
        p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .booking-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-download {
            background: #27ae60;
            color: white;
        }
        
        .btn-download:hover {
            background: #2ecc71;
            transform: translateY(-2px);
        }
        
        .btn-home {
            background: #3498db;
            color: white;
        }
        
        .btn-home:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-bookings {
            background: #666;
            color: white;
        }
        
        .btn-bookings:hover {
            background: #555;
            transform: translateY(-2px);
        }
        
        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }
        
        .close-btn:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="close-btn" onclick="window.close()">&times;</div>
        
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h2>Payment Successful!</h2>
        <p>Your ticket has been confirmed successfully.</p>
        
        <?php if($booking_id): ?>
        <div class="booking-info">
            <strong>Booking ID:</strong> <?php echo $booking_id; ?><br>
            <strong>Status:</strong> <span style="color: #27ae60;">Confirmed ✓</span>
        </div>
        <?php endif; ?>
        
        <div class="btn-group">
            <a href="download-ticket.php?booking_id=<?php echo $booking_id; ?>" class="btn btn-download" target="_blank">
                <i class="fas fa-download"></i> Download Ticket PDF
            </a>
            <a href="pages/my-bookings.php" class="btn btn-bookings">
                <i class="fas fa-list"></i> My Bookings
            </a>
            <a href="index.php" class="btn btn-home">
                <i class="fas fa-home"></i> Go to Home
            </a>
        </div>
        
        <p style="margin-top: 20px; font-size: 12px; color: #999;">
            <i class="fas fa-print"></i> You can also print this page as PDF
        </p>
    </div>
    
    <script>
        // Auto close after 5 seconds (optional)
        // setTimeout(function() {
        //     window.close();
        // }, 5000);
        
        // Prevent accidental close
        window.onbeforeunload = function() {
            return false;
        };
    </script>
</body>
</html>