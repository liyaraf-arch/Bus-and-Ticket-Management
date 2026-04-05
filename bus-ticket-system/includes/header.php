<?php
// Check if config is already included
if(!isset($conn)) {
    require_once 'includes/config.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo SITE_URL; ?>assets/images/favicon.ico">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Meta Tags -->
    <meta name="description" content="Book bus tickets online - Bangladesh's leading ticketing platform">
    <meta name="keywords" content="bus ticket, online booking, travel bangladesh">
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container">
            <div class="header-content" style="display: flex; justify-content: space-between; align-items: center;">
                <!-- Logo -->
                <div class="logo-section">
                    <a href="<?php echo SITE_URL; ?>" class="logo">
                        <span style="color: var(--dark-green); font-size: 24px; font-weight: bold;"><?php echo SITE_NAME; ?></span>
                    </a>
                    <span style="color: #999; font-size: 12px; margin-left: 10px;">BETA</span>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="nav-menu" style="display: flex; gap: 15px; align-items: center;">
                    <a href="<?php echo SITE_URL; ?>pages/bus.php">
                        <i class="fas fa-bus"></i> Bus
                    </a>
                    
                    </a>
                    <!-- includes/header.php - ইতিমধ্যে আছে -->
<a href="<?php echo SITE_URL; ?>pages/event.php"><?php echo __('event'); ?></a>
                    <a href="<?php echo SITE_URL; ?>pages/park.php">
                        <i class="fas fa-parking"></i> Park
                    </a>
                    
                    <!-- User Menu - এখানে কোড বসাতে হবে -->
                    <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                        <div class="user-menu" style="position: relative; display: inline-block;">
                            <button class="user-dropdown-btn" onclick="toggleUserDropdown()" style="
                                background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
                                color: white;
                                border: none;
                                padding: 10px 20px;
                                border-radius: 25px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                font-size: 14px;
                                font-weight: 500;
                            ">
                                <i class="fas fa-user-circle" style="font-size: 18px;"></i>
                                <span><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?></span>
                                <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                            </button>
                            
                            <div id="userDropdown" class="user-dropdown" style="
                                display: none;
                                position: absolute;
                                right: 0;
                                top: 100%;
                                margin-top: 10px;
                                background: white;
                                min-width: 220px;
                                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                                border-radius: 12px;
                                z-index: 1000;
                                overflow: hidden;
                            ">
                                <!-- User Info -->
                                <div style="padding: 15px; border-bottom: 1px solid #eee; background: #f8f9fa;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="
                                            width: 40px;
                                            height: 40px;
                                            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
                                            border-radius: 50%;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            color: white;
                                            font-weight: bold;
                                        ">
                                            <?php echo strtoupper(substr(isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'U', 0, 1)); ?>
                                        </div>
                                        <div>
                                            <strong style="color: #333; display: block; font-size: 14px;"><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?></strong>
                                            <small style="color: #666; font-size: 11px;"><?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- My Profile Link -->
                                <a href="<?php echo SITE_URL; ?>pages/profile.php" style="
                                    display: flex;
                                    align-items: center;
                                    gap: 12px;
                                    padding: 12px 15px;
                                    color: #333;
                                    text-decoration: none;
                                    transition: all 0.3s ease;
                                    border-bottom: 1px solid #eee;
                                ">
                                    <i class="fas fa-user" style="width: 20px; color: var(--primary-green);"></i>
                                    <span>My Profile</span>
                                </a>
                                
                                <!-- My Bookings Link -->
                                <a href="<?php echo SITE_URL; ?>pages/my-bookings.php" style="
                                    display: flex;
                                    align-items: center;
                                    gap: 12px;
                                    padding: 12px 15px;
                                    color: #333;
                                    text-decoration: none;
                                    transition: all 0.3s ease;
                                    border-bottom: 1px solid #eee;
                                ">
                                    <i class="fas fa-ticket-alt" style="width: 20px; color: var(--primary-green);"></i>
                                    <span>My Bookings</span>
                                </a>
                                
                                <!-- Settings Link (Optional) -->
                                <a href="<?php echo SITE_URL; ?>pages/settings.php" style="
                                    display: flex;
                                    align-items: center;
                                    gap: 12px;
                                    padding: 12px 15px;
                                    color: #333;
                                    text-decoration: none;
                                    transition: all 0.3s ease;
                                    border-bottom: 1px solid #eee;
                                ">
                                    <i class="fas fa-cog" style="width: 20px; color: var(--primary-green);"></i>
                                    <span>Settings</span>
                                </a>
                                <!-- includes/header.php - User Dropdown Menu -->
<a href="<?php echo SITE_URL; ?>pages/refund.php" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: #333; text-decoration: none;">
    <i class="fas fa-money-bill-wave" style="width: 20px; color: var(--primary-green);"></i>
    <span>Refund</span>
</a>
                                
                                <!-- Logout Link -->
                                <a href="<?php echo SITE_URL; ?>logout.php" style="
                                    display: flex;
                                    align-items: center;
                                    gap: 12px;
                                    padding: 12px 15px;
                                    color: #e74c3c;
                                    text-decoration: none;
                                    transition: all 0.3s ease;
                                    background: #fff5f5;
                                ">
                                    <i class="fas fa-sign-out-alt" style="width: 20px;"></i>
                                    <span>Logout</span>
                                </a>
                                
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>pages/login.php" class="btn">Login</a>
                        <a href="<?php echo SITE_URL; ?>pages/register.php" class="btn btn-green">Register</a>
                    <?php endif; ?>
                </nav>
                
                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" id="mobileMenuBtn" style="display: none; background: none; border: none; font-size: 24px; cursor: pointer;">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu" style="display: none; background: white; padding: 20px;">
        <a href="<?php echo SITE_URL; ?>pages/bus.php" style="display: block; padding: 10px; color: #333; text-decoration: none;">Bus</a>
        <a href="<?php echo SITE_URL; ?>pages/air.php" style="display: block; padding: 10px; color: #333; text-decoration: none;">Air</a>
        <a href="<?php echo SITE_URL; ?>pages/train.php" style="display: block; padding: 10px; color: #333; text-decoration: none;">Train</a>
        <a href="<?php echo SITE_URL; ?>pages/launch.php" style="display: block; padding: 10px; color: #333; text-decoration: none;">Launch</a>
        <a href="<?php echo SITE_URL; ?>pages/event.php" style="display: block; padding: 10px; color: #333; text-decoration: none;">Event</a>
        <a href="<?php echo SITE_URL; ?>pages/park.php" style="display: block; padding: 10px; color: #333; text-decoration: none;">Park</a>
        <hr>
        <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <a href="<?php echo SITE_URL; ?>pages/profile.php" style="display: block; padding: 10px; color: #333; text-decoration: none;">
                <i class="fas fa-user"></i> My Profile
            </a>
            <a href="<?php echo SITE_URL; ?>pages/my-bookings.php" style="display: block; padding: 10px; color: #333; text-decoration: none;">
                <i class="fas fa-ticket-alt"></i> My Bookings
            </a>
            <a href="<?php echo SITE_URL; ?>logout.php" style="display: block; padding: 10px; color: #e74c3c; text-decoration: none;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        <?php else: ?>
            <a href="<?php echo SITE_URL; ?>pages/login.php" style="display: block; padding: 10px; color: var(--primary-green); text-decoration: none;">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <a href="<?php echo SITE_URL; ?>pages/register.php" style="display: block; padding: 10px; color: var(--primary-green); text-decoration: none;">
                <i class="fas fa-user-plus"></i> Register
            </a>
        <?php endif; ?>
    </div>
    
    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .user-dropdown {
            animation: fadeInDown 0.3s ease;
        }
        
        .user-dropdown a:hover {
            background: #f8f9fa;
            padding-left: 20px;
        }
    </style>
    
    <script>
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if(dropdown) {
                if(dropdown.style.display === 'none' || !dropdown.style.display) {
                    dropdown.style.display = 'block';
                } else {
                    dropdown.style.display = 'none';
                }
            }
        }
        
        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if(!event.target.closest('.user-menu')) {
                const dropdown = document.getElementById('userDropdown');
                if(dropdown) {
                    dropdown.style.display = 'none';
                }
            }
        }
        
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            if(mobileMenu) {
                if(mobileMenu.style.display === 'none' || !mobileMenu.style.display) {
                    mobileMenu.style.display = 'block';
                } else {
                    mobileMenu.style.display = 'none';
                }
            }
        });
    </script>