<?php
require_once '../includes/config.php';
$page_title = 'Terms & Conditions';
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
        .page-container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .page-header h1 {
            color: white;
            margin-bottom: 10px;
        }
        
        .page-content {
            padding: 40px;
        }
        
        .terms-section {
            margin-bottom: 30px;
        }
        
        .terms-section h3 {
            color: var(--dark-green);
            margin-bottom: 15px;
        }
        
        .terms-section p, .terms-section ul {
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
        }
        
        .terms-section ul {
            margin-left: 20px;
        }
        
        .btn-home {
            display: inline-block;
            background: var(--primary-green);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 30px;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-container">
            <div class="page-header">
                <h1><i class="fas fa-file-contract"></i> Terms & Conditions</h1>
                <p>Please read these terms carefully</p>
            </div>
            
            <div class="page-content">
                <div class="terms-section">
                    <h3>1. Acceptance of Terms</h3>
                    <p>By accessing and using <?php echo SITE_NAME; ?>, you agree to be bound by these Terms & Conditions. If you do not agree, please do not use our services.</p>
                </div>
                
                <div class="terms-section">
                    <h3>2. Booking and Payment</h3>
                    <p>All bookings are subject to availability. Payment must be made in full at the time of booking. We accept various payment methods including bKash, Nagad, and Credit Cards.</p>
                </div>
                
                <div class="terms-section">
                    <h3>3. Cancellation Policy</h3>
                    <ul>
                        <li>Cancellation up to 24 hours before departure: 90% refund</li>
                        <li>Cancellation 12-24 hours before departure: 50% refund</li>
                        <li>Cancellation less than 12 hours before departure: No refund</li>
                        <li>No-show: No refund</li>
                    </ul>
                </div>
                
                <div class="terms-section">
                    <h3>4. Refund Policy</h3>
                    <p>Refunds will be processed within 7-10 business days. Refunds will be credited to the original payment method used for booking.</p>
                </div>
                
                <div class="terms-section">
                    <h3>5. User Responsibilities</h3>
                    <p>You are responsible for providing accurate information during booking. You must be at the boarding point at least 30 minutes before departure.</p>
                </div>
                
                <div class="terms-section">
                    <h3>6. Limitation of Liability</h3>
                    <p><?php echo SITE_NAME; ?> is not liable for any delays, cancellations, or changes made by bus operators. We act as an intermediary between passengers and bus operators.</p>
                </div>
                
                <div class="terms-section">
                    <h3>7. Changes to Terms</h3>
                    <p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on this page.</p>
                </div>
                
                <div style="text-align: center;">
                    <a href="../index.php" class="btn-home">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>