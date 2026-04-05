<?php
require_once '../includes/config.php';

$page_title = 'Search Buses';

// Get search parameters
$from = isset($_GET['from']) ? sanitizeInput($_GET['from']) : '';
$to = isset($_GET['to']) ? sanitizeInput($_GET['to']) : '';
$date = isset($_GET['date']) ? sanitizeInput($_GET['date']) : '';
$trip_type = isset($_GET['trip_type']) ? $_GET['trip_type'] : 'oneway';

// Search buses
$buses = [];
if($from && $to && $date) {
    $query = "SELECT * FROM routes WHERE from_city = '$from' AND to_city = '$to'";
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_assoc($result)) {
        // Get available seats
        $seats = getAvailableSeats($row['id'], $date);
        $row['available_seats'] = $seats['available'];
        $row['total_seats'] = $seats['total'];
        $buses[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <!-- Search Summary -->
        <div style="background: linear-gradient(135deg, var(--primary-green), var(--dark-green)); padding: 30px; border-radius: 10px; margin: 20px 0;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; text-align: center;">
        
        <!-- From -->
        <div>
            <i class="fas fa-map-marker-alt" style="font-size: 24px; color: #ffffff;"></i>
            <h3 style="color: #ffffff; margin: 10px 0;">From</h3>
            <p style="font-size: 18px; color: #ffffff; font-weight: 500;"><?php echo $from ?: 'Not selected'; ?></p>
        </div>
        
        <!-- To -->
        <div>
            <i class="fas fa-long-arrow-alt-right" style="font-size: 24px; color: #ffffff;"></i>
            <h3 style="color: #ffffff; margin: 10px 0;">To</h3>
            <p style="font-size: 18px; color: #ffffff; font-weight: 500;"><?php echo $to ?: 'Not selected'; ?></p>
        </div>
        
        <!-- Date -->
        <div>
            <i class="fas fa-calendar" style="font-size: 24px; color: #ffffff;"></i>
            <h3 style="color: #ffffff; margin: 10px 0;">Date</h3>
            <p style="font-size: 18px; color: #ffffff; font-weight: 500;"><?php echo $date ? formatDate($date) : 'Not selected'; ?></p>
        </div>
        
        <!-- Trip Type -->
        <div>
            <i class="fas fa-exchange-alt" style="font-size: 24px; color: #ffffff;"></i>
            <h3 style="color: #ffffff; margin: 10px 0;">Trip Type</h3>
            <p style="font-size: 18px; color: #ffffff; font-weight: 500;"><?php echo $trip_type == 'round' ? 'Round Trip' : 'Oneway'; ?></p>
        </div>
        
    </div>
</div>
        <!-- Modify Search -->
        <div style="background: white; padding: 20px; border-radius: 10px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: var(--dark-green); margin-bottom: 15px;">
                <i class="fas fa-search"></i> Modify Search
            </h3>
            <form action="search.php" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                <select name="from" class="form-control" style="padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
                    <option value="">From</option>
                    <option value="Dhaka" <?php echo $from == 'Dhaka' ? 'selected' : ''; ?>>Dhaka</option>
                    <option value="Chittagong" <?php echo $from == 'Chittagong' ? 'selected' : ''; ?>>Chittagong</option>
                    <option value="Sylhet" <?php echo $from == 'Sylhet' ? 'selected' : ''; ?>>Sylhet</option>
                    <option value="Cox's Bazar" <?php echo $from == "Cox's Bazar" ? 'selected' : ''; ?>>Cox's Bazar</option>
                </select>
                
                <select name="to" class="form-control" style="padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
                    <option value="">To</option>
                    <option value="Dhaka" <?php echo $to == 'Dhaka' ? 'selected' : ''; ?>>Dhaka</option>
                    <option value="Chittagong" <?php echo $to == 'Chittagong' ? 'selected' : ''; ?>>Chittagong</option>
                    <option value="Sylhet" <?php echo $to == 'Sylhet' ? 'selected' : ''; ?>>Sylhet</option>
                    <option value="Cox's Bazar" <?php echo $to == "Cox's Bazar" ? 'selected' : ''; ?>>Cox's Bazar</option>
                </select>
                
                <input type="date" name="date" value="<?php echo $date; ?>" 
                       style="padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
                
                <button type="submit" class="btn btn-green">
                    <i class="fas fa-search"></i> Search Again
                </button>
            </form>
        </div>
        
        <!-- Search Results -->
        <div style="margin: 30px 0;">
            <h2 style="color: var(--dark-green); margin-bottom: 20px;">
                Available Buses (<?php echo count($buses); ?> found)
            </h2>
            
            <?php if(empty($buses)): ?>
                <div style="text-align: center; padding: 50px; background: white; border-radius: 10px;">
                    <i class="fas fa-bus" style="font-size: 80px; color: #ddd;"></i>
                    <h3 style="color: #666; margin: 20px 0;">No buses found for this route</h3>
                    <p>Please try different cities or date</p>
                </div>
            <?php else: ?>
                <?php foreach($buses as $bus): ?>
                <div class="route-card" style="position: relative; overflow: hidden;">
                    <?php if($bus['available_seats'] < 5): ?>
                        <span style="position: absolute; top: 10px; right: -30px; background: #e74c3c; color: white; padding: 5px 40px; transform: rotate(45deg);">
                            Only <?php echo $bus['available_seats']; ?> left
                        </span>
                    <?php endif; ?>
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 20px; align-items: center;">
                        <div>
                            <h3 style="color: var(--dark-green);"><?php echo $bus['bus_name']; ?></h3>
                            <p style="color: #666;">
                                <i class="fas fa-bus"></i> <?php echo $bus['bus_type']; ?>
                            </p>
                            <p>
                                <i class="fas fa-chair"></i> 
                                Available: <strong><?php echo $bus['available_seats']; ?>/<?php echo $bus['total_seats']; ?></strong>
                            </p>
                        </div>
                        
                        <div style="text-align: center;">
                            <h4 style="color: var(--dark-green);"><?php echo formatTime($bus['departure_time']); ?></h4>
                            <p style="color: #666;">Departure</p>
                        </div>
                        
                        <div style="text-align: center;">
                            <i class="fas fa-long-arrow-alt-right" style="color: var(--primary-green); font-size: 24px;"></i>
                            <p style="color: #999;"><?php 
                                $start = strtotime($bus['departure_time']);
                                $end = strtotime($bus['arrival_time']);
                                $duration = ($end - $start) / 3600;
                                echo round($duration) . 'h';
                            ?></p>
                        </div>
                        
                        <div style="text-align: center;">
                            <h4 style="color: var(--dark-green);"><?php echo formatTime($bus['arrival_time']); ?></h4>
                            <p style="color: #666;">Arrival</p>
                        </div>
                        
                        <!-- pages/search.php - Select Seats Button Section -->

<div style="text-align: right;">
    <h3 style="color: var(--dark-green);">৳ <?php echo number_format($bus['fare']); ?></h3>
    <p style="color: #666;"><?php echo __('per_seat'); ?></p>
    
    <?php if($bus['available_seats'] > 0): ?>
        <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <!-- লগইন করা থাকলে সরাসরি সিট সিলেকশন পেজে -->
            <a href="seat-selection.php?route_id=<?php echo $bus['id']; ?>&date=<?php echo $date; ?>" 
               class="btn btn-green" style="margin-top: 10px; display: inline-block;">
                <i class="fas fa-chair"></i> <?php echo __('select_seats'); ?>
            </a>
        <?php else: ?>
            <!-- লগইন না থাকলে পপআপ দেখাবে -->
            <button class="btn btn-green" 
                    onclick="showLoginRequired(<?php echo $bus['id']; ?>, '<?php echo $date; ?>')" 
                    style="margin-top: 10px; cursor: pointer;">
                <i class="fas fa-chair"></i> <?php echo __('select_seats'); ?>
            </button>
        <?php endif; ?>
    <?php else: ?>
        <button class="btn" disabled style="margin-top: 10px;"><?php echo __('sold_out'); ?></button>
    <?php endif; ?>
</div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Related Routes -->
        <?php if(!empty($buses)): ?>
        <div style="margin: 40px 0;">
            <h3 style="color: var(--dark-green); margin-bottom: 20px;">Popular Routes</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="search.php?from=Dhaka&to=Chittagong&date=<?php echo $date; ?>" style="text-decoration: none;">
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i class="fas fa-bus" style="color: var(--primary-green);"></i>
                        <h4 style="color: #333;">Dhaka → Chittagong</h4>
                        <p style="color: #666;">From ৳1200</p>
                    </div>
                </a>
                <a href="search.php?from=Dhaka&to=Sylhet&date=<?php echo $date; ?>" style="text-decoration: none;">
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i class="fas fa-bus" style="color: var(--primary-green);"></i>
                        <h4 style="color: #333;">Dhaka → Sylhet</h4>
                        <p style="color: #666;">From ৳800</p>
                    </div>
                </a>
                <a href="search.php?from=Dhaka&to=Cox's Bazar&date=<?php echo $date; ?>" style="text-decoration: none;">
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i class="fas fa-bus" style="color: var(--primary-green);"></i>
                        <h4 style="color: #333;">Dhaka → Cox's Bazar</h4>
                        <p style="color: #666;">From ৳1500</p>
                    </div>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <!-- pages/search.php - Add this script at the end of file -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function showLoginRequired(routeId, date) {
        Swal.fire({
            title: '<?php echo __('login_required_title'); ?>',
            text: '<?php echo __('login_required_text'); ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<?php echo __('login_now'); ?>',
            cancelButtonText: '<?php echo __('cancel'); ?>',
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#666'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../pages/login.php?redirect=seat-selection&route_id=${routeId}&date=${date}`;
            }
        });
    }
</script>
<!-- pages/search.php - Simple JavaScript Alert -->

<script>
    function showLoginRequired(routeId, date) {
        if(confirm('⚠️ Login Required!\n\nPlease login to select seats and book tickets.')) {
            window.location.href = `../pages/login.php?redirect=seat-selection&route_id=${routeId}&date=${date}`;
        }
    }
</script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>