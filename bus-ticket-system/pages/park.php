<?php
// pages/park.php - Coming Soon Page
require_once '../includes/config.php';

$page_title = 'Park - Coming Soon';
?>

<!DOCTYPE html>
<html lang="<?php echo $current_lang == 'bn' ? 'bn' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
        }
        
        .coming-soon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 200px);
            padding: 40px 20px;
        }
        
        .coming-soon-card {
            background: white;
            border-radius: 30px;
            padding: 50px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            animation: fadeInUp 0.6s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .icon-wrapper {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(243, 156, 18, 0.4);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(243, 156, 18, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(243, 156, 18, 0);
            }
        }
        
        .icon-wrapper i {
            font-size: 50px;
            color: white;
        }
        
        .coming-soon-card h1 {
            font-size: 48px;
            color: #e67e22;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .coming-soon-card h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
        }
        
        .coming-soon-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(46, 204, 113, 0.4);
        }
        
        .btn-home i {
            margin-right: 8px;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e0e0e0;
            border-radius: 10px;
            margin: 30px 0 20px;
            overflow: hidden;
        }
        
        .progress-fill {
            width: 45%;
            height: 100%;
            background: linear-gradient(90deg, #f39c12, #e67e22);
            border-radius: 10px;
            animation: fillProgress 2s ease;
        }
        
        @keyframes fillProgress {
            from {
                width: 0%;
            }
            to {
                width: 45%;
            }
        }
        
        .launch-date {
            font-size: 14px;
            color: #999;
            margin-top: 15px;
        }
        
        .launch-date i {
            color: #e67e22;
            margin-right: 5px;
        }
        
        @media (max-width: 768px) {
            .coming-soon-card {
                padding: 30px 20px;
            }
            
            .coming-soon-card h1 {
                font-size: 36px;
            }
            
            .coming-soon-card h2 {
                font-size: 20px;
            }
            
            .icon-wrapper {
                width: 80px;
                height: 80px;
            }
            
            .icon-wrapper i {
                font-size: 35px;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="coming-soon-container">
        <div class="coming-soon-card">
            <div class="icon-wrapper">
                <i class="fas fa-parking"></i>
            </div>
            
            <h1>Coming Soon!</h1>
            <h2>Parking Ticket Booking</h2>
            
            <p>
                We're working hard to bring you the best parking ticket booking experience. 
                Soon you'll be able to book parking spots across Bangladesh with ease.
            </p>
            
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            
            <div class="launch-date">
                <i class="fas fa-calendar-alt"></i> Expected Launch: June 2026
            </div>
            
            <a href="../index.php" class="btn-home">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>