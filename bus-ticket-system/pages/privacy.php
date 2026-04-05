<?php
require_once '../includes/config.php';
$page_title = 'Privacy Policy';
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
        
        .privacy-section {
            margin-bottom: 30px;
        }
        
        .privacy-section h3 {
            color: var(--dark-green);
            margin-bottom: 15px;
        }
        
        .privacy-section p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
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
                <h1><i class="fas fa-lock"></i> Privacy Policy</h1>
                <p>Your privacy is important to us</p>
            </div>
            
            <div class="page-content">
                <div class="privacy-section">
                    <h3>1. Information We Collect</h3>
                    <p>We collect personal information including your name, email address, phone number, and payment information when you create an account or make a booking. We also collect usage data to improve our services.</p>
                </div>
                
                <div class="privacy-section">
                    <h3>2. How We Use Your Information</h3>
                    <p>Your information is used to process bookings, communicate with you about your reservations, improve our services, and send promotional offers (with your consent).</p>
                </div>
                
                <div class="privacy-section">
                    <h3>3. Data Security</h3>
                    <p>We implement industry-standard security measures to protect your personal information. All payment transactions are encrypted using SSL technology.</p>
                </div>
                
                <div class="privacy-section">
                    <h3>4. Sharing of Information</h3>
                    <p>We do not sell your personal information. We may share information with bus operators for booking purposes, or with payment processors for transaction completion.</p>
                </div>
                
                <div class="privacy-section">
                    <h3>5. Cookies</h3>
                    <p>We use cookies to enhance your browsing experience. You can disable cookies in your browser settings, but this may affect some functionality.</p>
                </div>
                
                <div class="privacy-section">
                    <h3>6. Your Rights</h3>
                    <p>You have the right to access, correct, or delete your personal information. Contact us at privacy@trotter.com for any privacy-related requests.</p>
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