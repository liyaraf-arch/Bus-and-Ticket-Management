<?php
// pages/refund.php - Refund Request Page
require_once '../includes/config.php';

if(!isLoggedIn()) {
    $_SESSION['error'] = 'Please login first';
    header("Location: login.php");
    exit();
}

$page_title = 'Refund Requests';
$user_id = $_SESSION['user_id'];

// Handle refund request
if(isset($_POST['request_refund'])) {
    $booking_id = sanitizeInput($_POST['booking_id']);
    $reason = sanitizeInput($_POST['reason']);
    
    // Check if booking exists and is within 4 hours
    $check_query = "SELECT b.*, 
                    TIMESTAMPDIFF(HOUR, b.booking_date, NOW()) as hours_passed
                    FROM bookings b 
                    WHERE b.booking_id = '$booking_id' AND b.user_id = '$user_id'
                    AND b.status = 'confirmed'";
    $check_result = mysqli_query($conn, $check_query);
    $booking = mysqli_fetch_assoc($check_result);
    
    if($booking) {
        $hours_passed = $booking['hours_passed'];
        
        if($hours_passed <= 4) {
            // Check if refund already requested
            $check_refund = "SELECT * FROM refund_requests WHERE booking_id = '$booking_id' AND status IN ('pending', 'approved')";
            $refund_result = mysqli_query($conn, $check_refund);
            
            if(mysqli_num_rows($refund_result) == 0) {
                $amount = $booking['total_fare'];
                $insert_query = "INSERT INTO refund_requests (booking_id, user_id, amount, reason) 
                                VALUES ('$booking_id', '$user_id', '$amount', '$reason')";
                
                if(mysqli_query($conn, $insert_query)) {
                    $_SESSION['success'] = 'Your refund request has been sent to authority.';
                } else {
                    $_SESSION['error'] = 'Failed to submit refund request: ' . mysqli_error($conn);
                }
            } else {
                $_SESSION['error'] = 'Refund request already submitted for this booking.';
            }
        } else {
            $_SESSION['error'] = 'Refund request period has expired (4 hours). You cannot request refund now.';
        }
    } else {
        $_SESSION['error'] = 'Booking not found or not eligible for refund.';
    }
    
    // Redirect back to refund page
    header("Location: refund.php");
    exit();
}

// Get all confirmed bookings for the user
$bookings_query = "SELECT b.*, rt.from_city, rt.to_city, rt.bus_name, rt.bus_type,
                   TIMESTAMPDIFF(HOUR, b.booking_date, NOW()) as hours_passed
                   FROM bookings b 
                   JOIN routes rt ON b.route_id = rt.id
                   WHERE b.user_id = '$user_id' 
                   AND b.status = 'confirmed'
                   AND b.payment_status = 'completed'
                   ORDER BY b.booking_date DESC";
$bookings_result = mysqli_query($conn, $bookings_query);

// Get user's refund requests
$refund_query = "SELECT r.*, b.booking_id, b.total_fare, b.journey_date, b.seat_numbers,
                 rt.from_city, rt.to_city, rt.bus_name
                 FROM refund_requests r 
                 JOIN bookings b ON r.booking_id = b.booking_id 
                 JOIN routes rt ON b.route_id = rt.id
                 WHERE r.user_id = '$user_id' 
                 ORDER BY r.request_date DESC";
$refund_result = mysqli_query($conn, $refund_query);
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
        .refund-container {
            max-width: 1200px;
            margin: 40px auto;
        }
        
        .refund-card {
            background: white;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .refund-card:hover {
            transform: translateY(-3px);
        }
        
        .refund-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .refund-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-pending {
            background: #f39c12;
            color: white;
        }
        
        .status-approved {
            background: #27ae60;
            color: white;
        }
        
        .status-rejected {
            background: #e74c3c;
            color: white;
        }
        
        .refund-body {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .refund-info {
            padding: 10px;
        }
        
        .refund-info h4 {
            color: var(--dark-green);
            margin-bottom: 10px;
        }
        
        .refund-info p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }
        
        .refund-info i {
            width: 25px;
            color: var(--primary-green);
        }
        
        .refund-actions {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            text-align: right;
        }
        
        .btn-refund {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-refund:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        
        .btn-refund:disabled {
            background: #95a5a6;
            cursor: not-allowed;
        }
        
        .timer {
            font-weight: bold;
        }
        
        .timer-warning {
            color: #e74c3c;
        }
        
        .timer-safe {
            color: #27ae60;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            width: 450px;
            max-width: 90%;
            padding: 25px;
            border-radius: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 15px;
        }
        
        .no-data i {
            font-size: 50px;
            color: #ccc;
            margin-bottom: 15px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .badge-pending {
            background: #f39c12;
            color: white;
        }
        
        .badge-approved {
            background: #27ae60;
            color: white;
        }
        
        .badge-rejected {
            background: #e74c3c;
            color: white;
        }
        
        .badge-expired {
            background: #95a5a6;
            color: white;
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 80px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            z-index: 9999;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .toast-notification.error {
            background: #e74c3c;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        @media (max-width: 768px) {
            .refund-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="refund-container">
            <h2 style="color: var(--dark-green); margin-bottom: 20px;">
                <i class="fas fa-money-bill-wave"></i> Refund Requests
            </h2>
            
            <!-- My Bookings for Refund -->
            <h3 style="color: var(--dark-green); margin: 20px 0 15px;">
                <i class="fas fa-ticket-alt"></i> My Bookings
            </h3>
            
            <?php if(mysqli_num_rows($bookings_result) > 0): ?>
                <?php while($booking = mysqli_fetch_assoc($bookings_result)): 
                    $hours_passed = $booking['hours_passed'];
                    $can_refund = ($hours_passed <= 4);
                    $refund_status = '';
                    
                    // Check if refund already requested
                    $check_refund_status = "SELECT status FROM refund_requests WHERE booking_id = '{$booking['booking_id']}' ORDER BY id DESC LIMIT 1";
                    $status_result = mysqli_query($conn, $check_refund_status);
                    if(mysqli_num_rows($status_result) > 0) {
                        $refund_data = mysqli_fetch_assoc($status_result);
                        $refund_status = $refund_data['status'];
                    }
                ?>
                    <div class="refund-card">
                        <div class="refund-header">
                            <span><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></span>
                            <span class="timer <?php echo $can_refund ? 'timer-warning' : 'timer-safe'; ?>">
                                <i class="fas fa-hourglass-half"></i> 
                                <?php if($can_refund): ?>
                                    <?php echo (4 - $hours_passed); ?> hours left for refund
                                <?php else: ?>
                                    Refund period expired
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="refund-body">
                            <div class="refund-info">
                                <h4><i class="fas fa-bus"></i> Journey Details</h4>
                                <p><i class="fas fa-bus"></i> <?php echo $booking['bus_name']; ?> (<?php echo $booking['bus_type']; ?>)</p>
                                <p><i class="fas fa-road"></i> <?php echo $booking['from_city']; ?> → <?php echo $booking['to_city']; ?></p>
                                <p><i class="fas fa-calendar"></i> <?php echo date('d M, Y', strtotime($booking['journey_date'])); ?></p>
                                <p><i class="fas fa-chair"></i> Seats: <?php echo $booking['seat_numbers']; ?></p>
                            </div>
                            <div class="refund-info">
                                <h4><i class="fas fa-credit-card"></i> Payment Details</h4>
                                <p><i class="fas fa-tag"></i> Amount: BDT <?php echo number_format($booking['total_fare']); ?></p>
                                <p><i class="fas fa-clock"></i> Booked on: <?php echo date('d M, Y h:i A', strtotime($booking['booking_date'])); ?></p>
                            </div>
                        </div>
                        <div class="refund-actions">
                            <?php if($refund_status == 'pending'): ?>
                                <span class="badge badge-pending"><i class="fas fa-clock"></i> Refund Request Pending</span>
                            <?php elseif($refund_status == 'approved'): ?>
                                <span class="badge badge-approved"><i class="fas fa-check-circle"></i> Refund Approved</span>
                            <?php elseif($refund_status == 'rejected'): ?>
                                <span class="badge badge-rejected"><i class="fas fa-times-circle"></i> Refund Rejected</span>
                            <?php elseif($can_refund): ?>
                                <button class="btn-refund" onclick="openRefundModal('<?php echo $booking['booking_id']; ?>')">
                                    <i class="fas fa-undo-alt"></i> Request Refund
                                </button>
                            <?php else: ?>
                                <span class="badge badge-expired"><i class="fas fa-hourglass-end"></i> Refund Period Expired</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-ticket-alt"></i>
                    <p>No bookings found.</p>
                    <a href="search.php" class="btn btn-green" style="margin-top: 15px; display: inline-block;">
                        <i class="fas fa-search"></i> Book a Ticket
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Refund Request History -->
            <?php if(mysqli_num_rows($refund_result) > 0): ?>
                <h3 style="color: var(--dark-green); margin: 40px 0 15px;">
                    <i class="fas fa-history"></i> Refund Request History
                </h3>
                <?php while($refund = mysqli_fetch_assoc($refund_result)): ?>
                    <div class="refund-card">
                        <div class="refund-header">
                            <span><strong>Booking ID:</strong> <?php echo $refund['booking_id']; ?></span>
                            <span class="refund-status status-<?php echo $refund['status']; ?>">
                                <?php echo strtoupper($refund['status']); ?>
                            </span>
                        </div>
                        <div class="refund-body">
                            <div class="refund-info">
                                <h4><i class="fas fa-info-circle"></i> Refund Details</h4>
                                <p><i class="fas fa-money-bill"></i> Amount: BDT <?php echo number_format($refund['amount']); ?></p>
                                <p><i class="fas fa-calendar"></i> Requested: <?php echo date('d M, Y h:i A', strtotime($refund['request_date'])); ?></p>
                                <p><i class="fas fa-comment"></i> Reason: <?php echo $refund['reason'] ?: 'Not specified'; ?></p>
                            </div>
                            <div class="refund-info">
                                <h4><i class="fas fa-ticket-alt"></i> Booking Details</h4>
                                <p><i class="fas fa-bus"></i> <?php echo $refund['bus_name']; ?></p>
                                <p><i class="fas fa-road"></i> <?php echo $refund['from_city']; ?> → <?php echo $refund['to_city']; ?></p>
                                <p><i class="fas fa-chair"></i> Seats: <?php echo $refund['seat_numbers']; ?></p>
                            </div>
                        </div>
                        <?php if($refund['status'] == 'approved'): ?>
                            <div class="refund-actions" style="background: #d4edda; text-align: center;">
                                <span><i class="fas fa-check-circle"></i> Refund approved! Amount will be credited within 5-7 business days.</span>
                            </div>
                        <?php elseif($refund['status'] == 'rejected'): ?>
                            <div class="refund-actions" style="background: #f8d7da; text-align: center;">
                                <span><i class="fas fa-times-circle"></i> Refund request rejected.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Refund Modal -->
    <div id="refundModal" class="modal">
        <div class="modal-content">
            <h3 style="color: var(--dark-green); margin-bottom: 20px;">
                <i class="fas fa-undo-alt"></i> Request Refund
            </h3>
            <form method="POST" action="refund.php">
                <input type="hidden" name="booking_id" id="refund_booking_id">
                <div class="form-group">
                    <label>Reason for refund <span style="color: red;">*</span></label>
                    <textarea name="reason" rows="4" placeholder="Please explain why you want to refund this booking..." required></textarea>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" name="request_refund" class="btn-refund">
                        <i class="fas fa-paper-plane"></i> Submit Refund Request
                    </button>
                    <button type="button" onclick="closeRefundModal()" style="background: #666; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openRefundModal(bookingId) {
            document.getElementById('refund_booking_id').value = bookingId;
            document.getElementById('refundModal').style.display = 'flex';
        }
        
        function closeRefundModal() {
            document.getElementById('refundModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            if(event.target === document.getElementById('refundModal')) {
                closeRefundModal();
            }
        }
        
        // Show toast notification for success/error
        <?php if(isset($_SESSION['success'])): ?>
            showToast('<?php echo $_SESSION['success']; ?>', 'success');
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            showToast('<?php echo $_SESSION['error']; ?>', 'error');
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = 'toast-notification ' + (type === 'error' ? 'error' : '');
            toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>