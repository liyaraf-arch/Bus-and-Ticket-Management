<?php
require_once '../includes/config.php';
$page_title = 'About Us';
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
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
        
        .feature-card {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .feature-card i {
            font-size: 50px;
            color: var(--primary-green);
            margin-bottom: 15px;
        }
        
        .feature-card h3 {
            color: var(--dark-green);
            margin-bottom: 10px;
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
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 40px 0;
            text-align: center;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: var(--dark-green);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-container">
            <div class="page-header">
                <h1><i class="fas fa-info-circle"></i> About <?php echo SITE_NAME; ?></h1>
                <p>Your Trusted Travel Partner in Bangladesh</p>
            </div>
            
            <div class="page-content">
                <h2 style="color: var(--dark-green);">Who We Are</h2>
                <p style="line-height: 1.8; color: #555;">
                    <?php echo SITE_NAME; ?> is Bangladesh's leading online ticketing platform. Founded in 2024, 
                    we have revolutionized the way people book bus tickets across the country. Our mission is to 
                    make travel simple, secure, and accessible for everyone.
                </p>
                
                <div class="stats">
                    <div>
                        <div class="stat-number">50,000+</div>
                        <p>Happy Customers</p>
                    </div>
                    <div>
                        <div class="stat-number">100+</div>
                        <p>Bus Routes</p>
                    </div>
                    <div>
                        <div class="stat-number">24/7</div>
                        <p>Customer Support</p>
                    </div>
                    <div>
                        <div class="stat-number">100%</div>
                        <p>Secure Payments</p>
                    </div>
                </div>
                
                <h2 style="color: var(--dark-green); margin-top: 40px;">Why Choose Us?</h2>
                
                <div class="feature-grid">
                    <div class="feature-card">
                        <i class="fas fa-mobile-alt"></i>
                        <h3>Easy Booking</h3>
                        <p>Book tickets in minutes</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Secure Payment</h3>
                        <p>100% secure transactions</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-headset"></i>
                        <h3>24/7 Support</h3>
                        <p>Always here to help</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-ticket-alt"></i>
                        <h3>Easy Cancellation</h3>
                        <p>Cancel anytime</p>
                    </div>
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