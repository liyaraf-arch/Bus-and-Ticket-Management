<?php
require_once 'includes/config.php';
$page_title = 'Home - Book Bus Tickets Online';

// যদি ইউজার লগইন করা থাকে তাহলে স্বাগতম মেসেজ দেখান
if(isset($_SESSION['success'])) {
    echo '<div class="alert alert-success" style="position: fixed; top: 80px; right: 20px; z-index: 9999; animation: slideIn 0.5s ease;">
            <i class="fas fa-check-circle"></i> ' . $_SESSION['success'] . '
          </div>';
    unset($_SESSION['success']);
    
    // 3 সেকেন্ড পরে অলার্ট হাইড করুন
    echo '<script>
            setTimeout(function() {
                const alert = document.querySelector(".alert");
                if(alert) {
                    alert.style.animation = "slideOut 0.5s ease";
                    setTimeout(() => alert.remove(), 500);
                }
            }, 3000);
          </script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - <?php echo $page_title; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section with Search -->
    <section class="hero" style="background: linear-gradient(135deg, var(--primary-green), var(--dark-green)); color: white; padding: 60px 0;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="color: white; font-size: 3em; margin-bottom: 15px;">Welcome to <?php echo SITE_NAME; ?></h1>
                <p style="font-size: 1.2em; opacity: 0.9;">Bangladesh's Leading Online Ticketing Platform</p>
            </div>
            
            <div class="search-box"
            <!-- সরাসরি search-box এর ভিতরে যোগ করুন -->
<div style="margin-bottom: 20px;">
    <button type="button" onclick="setTripType('oneway')" id="onewayBtn" style="background: var(--primary-green); color: white; border: none; padding: 10px 20px; border-radius: 5px;">One Way</button>
    <button type="button" onclick="setTripType('round')" id="roundBtn" style="background: white; color: var(--primary-green); border: 2px solid var(--primary-green); padding: 10px 20px; border-radius: 5px;">Round Trip</button>
</div>

<script>
function setTripType(type) {
    const onewayBtn = document.getElementById('onewayBtn');
    const roundBtn = document.getElementById('roundBtn');
    const returnGroup = document.querySelector('.return-date-group');
    
    if(type === 'oneway') {
        onewayBtn.style.background = 'var(--primary-green)';
        onewayBtn.style.color = 'white';
        roundBtn.style.background = 'white';
        roundBtn.style.color = 'var(--primary-green)';
        returnGroup.style.display = 'none';
    } else {
        roundBtn.style.background = 'var(--primary-green)';
        roundBtn.style.color = 'white';
        onewayBtn.style.background = 'white';
        onewayBtn.style.color = 'var(--primary-green)';
        returnGroup.style.display = 'block';
    }
}
</script>
                
                <form id="searchForm" action="pages/search.php" method="GET">
                    <div class="search-row">
                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt" style="color: var(--primary-green);"></i> From</label>
                            <select name="from" required>
                                <option value="">Select City</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chittagong">Chittagong</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Cox's Bazar">Cox's Bazar</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Barisal">Barisal</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Mymensingh">Mymensingh</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt" style="color: var(--primary-green);"></i> To</label>
                            <select name="to" required>
                                <option value="">Select City</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chittagong">Chittagong</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Cox's Bazar">Cox's Bazar</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Barisal">Barisal</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Mymensingh">Mymensingh</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-calendar" style="color: var(--primary-green);"></i> Journey Date</label>
                            <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="form-group return-date-group" style="display: none;">
                            <label><i class="fas fa-calendar" style="color: var(--primary-green);"></i> Return Date</label>
                            <input type="date" name="return_date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-green" style="width: 100%;">
                                <i class="fas fa-search"></i> SEARCH
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Services Section -->
    <!-- index.php - Our Services Section -->

<!-- Services Section -->
<section style="padding: 60px 0; background: #ffffff;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 40px; color: var(--dark-green);">Our Services</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 25px;">
            <!-- Bus Service -->
            <div class="service-card" onclick="window.location.href='pages/bus.php'" style="
                background: white;
                padding: 30px;
                border-radius: 15px;
                text-align: center;
                border: 2px solid #27ae60;
                cursor: pointer;
                transition: all 0.3s ease;
            ">
                <i class="fas fa-bus" style="font-size: 50px; color: #27ae60; transition: all 0.3s ease;"></i>
                <h3 style="margin-top: 15px; color: #2c3e50;">Bus</h3>
                <p style="color: #666;">Book bus tickets</p>
            </div>
            
           
            
            <!-- Event Service -->
            <div class="service-card" onclick="window.location.href='pages/event.php'" style="
                background: white;
                padding: 30px;
                border-radius: 15px;
                text-align: center;
                border: 2px solid #27ae60;
                cursor: pointer;
                transition: all 0.3s ease;
            ">
                <i class="fas fa-calendar-alt" style="font-size: 50px; color: #27ae60; transition: all 0.3s ease;"></i>
                <h3 style="margin-top: 15px; color: #2c3e50;">Event</h3>
                <p style="color: #666;">Event tickets</p>
            </div>
            
            <!-- Park Service -->
            <div class="service-card" onclick="window.location.href='pages/park.php'" style="
                background: white;
                padding: 30px;
                border-radius: 15px;
                text-align: center;
                border: 2px solid #27ae60;
                cursor: pointer;
                transition: all 0.3s ease;
            ">
                <i class="fas fa-parking" style="font-size: 50px; color: #27ae60; transition: all 0.3s ease;"></i>
                <h3 style="margin-top: 15px; color: #2c3e50;">Park</h3>
                <p style="color: #666;">Parking tickets</p>
            </div>
        </div>
    </div>
</section>

<!-- Hover Effect CSS -->
<style>
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .service-card:hover i {
        color: #e74c3c !important;
        transform: scale(1.1);
    }
    
    .service-card:hover h3 {
        color: #e74c3c !important;
    }
    
    .service-card:hover {
        border-color: #e74c3c !important;
    }
</style>
    
    <!-- Trending Destinations -->
    <!-- index.php - Trending Destinations Section আপডেট করুন -->

<!-- Trending Destinations -->
<!-- index.php - Trending Destinations Section -->

<!-- index.php - Trending Destinations Section -->

<section style="padding: 60px 0; background: #f8f9fa;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 40px;">Discover Trending Destinations</h2>
        
        <div id="trendingDestinations" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px;">
            <?php
            // Load trending destinations from database
            $trending_query = "SELECT * FROM trending_destinations WHERE status = 'active' ORDER BY order_position ASC";
            $trending_result = mysqli_query($conn, $trending_query);
            
            if(mysqli_num_rows($trending_result) > 0):
                while($trend = mysqli_fetch_assoc($trending_result)):
            ?>
                <div class="trending-card" 
                     data-from="<?php echo $trend['from_city']; ?>"
                     data-to="<?php echo $trend['to_city']; ?>"
                     style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); transition: all 0.3s ease; cursor: pointer;">
                    
                    <img src="<?php echo $trend['image_path']; ?>" 
                         alt="<?php echo $trend['title']; ?>" 
                         style="width: 100%; height: 200px; object-fit: cover;">
                    
                    <div style="padding: 20px;">
                        <h3 style="color: var(--dark-green); margin-bottom: 10px;"><?php echo $trend['title']; ?></h3>
                        <p style="color: #666; margin-bottom: 15px;"><?php echo substr($trend['description'], 0, 80); ?>...</p>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <span style="color: var(--primary-green); font-weight: bold;">
                                <i class="fas fa-map-marker-alt"></i> <?php echo $trend['from_city']; ?> → <?php echo $trend['to_city']; ?>
                            </span>
                            <span style="color: #e67e22; font-weight: bold; font-size: 20px;">
                                BDT <?php echo number_format($trend['price']); ?>
                            </span>
                        </div>
                        
                        <button class="btn-view-buses" 
                                data-from="<?php echo $trend['from_city']; ?>"
                                data-to="<?php echo $trend['to_city']; ?>"
                                style="width: 100%; background: var(--primary-green); color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: bold; transition: all 0.3s ease;">
                            <i class="fas fa-search"></i> View Buses
                        </button>
                    </div>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <div style="text-align: center; padding: 50px; background: white; border-radius: 15px; grid-column: 1/-1;">
                    <i class="fas fa-image" style="font-size: 50px; color: #ccc;"></i>
                    <p style="margin-top: 15px;">No trending destinations available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .trending-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .trending-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .btn-view-buses:hover {
        background: var(--dark-green) !important;
        transform: translateY(-2px);
    }
</style>

<script>
    // Function to search buses
    function searchBusRoute(from, to) {
        if(!from || !to) {
            console.error('From or To city is missing');
            return false;
        }
        
        // Get today's date
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const date = `${year}-${month}-${day}`;
        
        // Redirect to bus search page
        const url = `pages/bus.php?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&date=${date}`;
        console.log('Redirecting to:', url);
        window.location.href = url;
        return true;
    }
    
    // Add click event listeners when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // For the whole card click
        const cards = document.querySelectorAll('.trending-card');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't trigger if clicking on the button
                if(e.target.closest('.btn-view-buses')) {
                    return;
                }
                const from = this.dataset.from;
                const to = this.dataset.to;
                if(from && to) {
                    searchBusRoute(from, to);
                }
            });
        });
        
        // For the button click
        const buttons = document.querySelectorAll('.btn-view-buses');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent card click from firing
                const from = this.dataset.from;
                const to = this.dataset.to;
                if(from && to) {
                    searchBusRoute(from, to);
                }
            });
        });
    });
</script>
    
    <!-- Features -->
    <!-- index.php - Features Section এর কালার -->
<section style="padding: 60px 0; background: #ffffff;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; text-align: center;">
            
            <!-- Feature 1 - Secure Payment -->
            <div class="feature-card" onclick="changeFeatureColor(this)" style="
                padding: 25px;
                border-radius: 15px;
                transition: all 0.3s ease;
                cursor: pointer;
                background: white;
                border: 2px solid #e0e0e0;
            ">
                <i class="fas fa-shield-alt" style="font-size: 50px; color: #27ae60; transition: all 0.3s ease;"></i>
                <h3 style="color: #2c3e50; margin-top: 15px; transition: all 0.3s ease;">Secure Payment</h3>
                <p style="color: #666; transition: all 0.3s ease;">100% secure transactions</p>
            </div>
            
            <!-- Feature 2 - 24/7 Support -->
            <div class="feature-card" onclick="changeFeatureColor(this)" style="
                padding: 25px;
                border-radius: 15px;
                transition: all 0.3s ease;
                cursor: pointer;
                background: white;
                border: 2px solid #e0e0e0;
            ">
                <i class="fas fa-clock" style="font-size: 50px; color: #27ae60; transition: all 0.3s ease;"></i>
                <h3 style="color: #2c3e50; margin-top: 15px; transition: all 0.3s ease;">24/7 Support</h3>
                <p style="color: #666; transition: all 0.3s ease;">Customer support anytime</p>
            </div>
            
            <!-- Feature 3 - Easy Cancellation -->
            <div class="feature-card" onclick="changeFeatureColor(this)" style="
                padding: 25px;
                border-radius: 15px;
                transition: all 0.3s ease;
                cursor: pointer;
                background: white;
                border: 2px solid #e0e0e0;
            ">
                <i class="fas fa-ticket-alt" style="font-size: 50px; color: #27ae60; transition: all 0.3s ease;"></i>
                <h3 style="color: #2c3e50; margin-top: 15px; transition: all 0.3s ease;">Easy Cancellation</h3>
                <p style="color: #666; transition: all 0.3s ease;">Cancel tickets easily</p>
            </div>
            
            <!-- Feature 4 - Mobile App -->
            <div class="feature-card" onclick="changeFeatureColor(this)" style="
                padding: 25px;
                border-radius: 15px;
                transition: all 0.3s ease;
                cursor: pointer;
                background: white;
                border: 2px solid #e0e0e0;
            ">
                <i class="fas fa-mobile-alt" style="font-size: 50px; color: #27ae60; transition: all 0.3s ease;"></i>
                <h3 style="color: #2c3e50; margin-top: 15px; transition: all 0.3s ease;">Mobile App</h3>
                <p style="color: #666; transition: all 0.3s ease;">Book on the go</p>
            </div>
            
        </div>
    </div>
</section>

<!-- JavaScript for Click Effect -->
<script>
    let activeFeature = null;
    
    function changeFeatureColor(element) {
        // Reset previous active feature
        if(activeFeature && activeFeature !== element) {
            resetFeatureColor(activeFeature);
        }
        
        // Check if same feature is clicked again
        if(activeFeature === element) {
            resetFeatureColor(element);
            activeFeature = null;
        } else {
            // Apply new colors
            element.style.background = '#e74c3c';
            element.style.borderColor = '#e74c3c';
            element.querySelector('i').style.color = 'white';
            element.querySelector('h3').style.color = 'white';
            element.querySelector('p').style.color = 'white';
            activeFeature = element;
        }
    }
    
    function resetFeatureColor(element) {
        element.style.background = 'white';
        element.style.borderColor = '#e0e0e0';
        element.querySelector('i').style.color = '#27ae60';
        element.querySelector('h3').style.color = '#2c3e50';
        element.querySelector('p').style.color = '#666';
    }
</script>

<style>
    .feature-card {
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: #27ae60 !important;
    }
    
    .feature-card:active {
        transform: translateY(-2px);
    }
</style>
    
    <!-- Download App -->
<!-- index.php - Download App Section সম্পূর্ণ অংশ -->

<section style="background: #ffffff; padding: 60px 0;">
    <div class="container">
        <div style="max-width: 1000px; margin: 0 auto;">
            <div style="text-align: center;">
                <h2 style="color: var(--dark-green); font-size: 36px; margin-bottom: 15px;">
                    <i class="fas fa-mobile-alt" style="color: var(--primary-green);"></i> 
                    Download Our App
                </h2>
                <p style="color: #666; font-size: 18px; margin-bottom: 30px;">
                    Get the best experience with our mobile app
                </p>
                
                <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
                    <!-- Google Play -->
                    <a href="#" style="display: inline-block; transition: all 0.3s ease;">
                        <div style="background: #f8f9fa; padding: 12px 30px; border-radius: 50px; display: flex; align-items: center; gap: 12px;">
                            <i class="fab fa-google-play" style="font-size: 30px; color: #27ae60;"></i>
                            <div>
                                <div style="font-size: 12px; color: #666;">GET IT ON</div>
                                <div style="font-size: 18px; font-weight: bold; color: #333;">Google Play</div>
                            </div>
                        </div>
                    </a>
                    
                    <!-- App Store -->
                    <a href="#" style="display: inline-block; transition: all 0.3s ease;">
                        <div style="background: #f8f9fa; padding: 12px 30px; border-radius: 50px; display: flex; align-items: center; gap: 12px;">
                            <i class="fab fa-apple" style="font-size: 30px; color: #27ae60;"></i>
                            <div>
                                <div style="font-size: 12px; color: #666;">Download on the</div>
                                <div style="font-size: 18px; font-weight: bold; color: #333;">App Store</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .download-btn {
        transition: all 0.3s ease;
    }
    
    .download-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
    
    <script>
        // Show/hide return date for round trip
        document.querySelectorAll('input[name="tripType"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const returnDateGroup = document.querySelector('.return-date-group');
                if(this.value === 'round') {
                    returnDateGroup.style.display = 'block';
                } else {
                    returnDateGroup.style.display = 'none';
                }
            });
        });
    </script>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>