<?php
require_once '../includes/config.php';
$page_title = 'Bus Tickets - All Routes';

// Get all bus routes
$query = "SELECT * FROM routes ORDER BY from_city, to_city";
$result = mysqli_query($conn, $query);

// Get unique cities for filter
$cities_query = "SELECT DISTINCT from_city as city FROM routes UNION SELECT DISTINCT to_city FROM routes ORDER BY city";
$cities_result = mysqli_query($conn, $cities_query);
$cities = [];
while($city = mysqli_fetch_assoc($cities_result)) {
    $cities[] = $city['city'];
}

// Handle search filter
$from_filter = isset($_GET['from']) ? sanitizeInput($_GET['from']) : '';
$to_filter = isset($_GET['to']) ? sanitizeInput($_GET['to']) : '';
$date_filter = isset($_GET['date']) ? sanitizeInput($_GET['date']) : date('Y-m-d');

if($from_filter && $to_filter) {
    $query = "SELECT * FROM routes WHERE from_city = '$from_filter' AND to_city = '$to_filter' ORDER BY departure_time";
    $result = mysqli_query($conn, $query);
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
    <style>
        .bus-hero {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            padding: 40px 0;
            color: white;
            text-align: center;
        }
        
        .search-filter {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-top: -30px;
            margin-bottom: 40px;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        
        .filter-group select, .filter-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .filter-group select:focus, .filter-group input:focus {
            outline: none;
            border-color: var(--primary-green);
        }
        
        .btn-filter {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .btn-reset {
            background: #666;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .routes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .route-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .route-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .route-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 15px;
            text-align: center;
        }
        
        .route-header h3 {
            color: white;
            margin: 0;
        }
        
        .route-body {
            padding: 20px;
        }
        
        .route-info {
            margin-bottom: 15px;
        }
        
        .route-info p {
            margin: 8px 0;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .route-info i {
            width: 25px;
            color: var(--primary-green);
        }
        
        .route-time {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
            margin: 15px 0;
        }
        
        .time-box {
            text-align: center;
        }
        
        .time-box .time {
            font-size: 18px;
            font-weight: bold;
            color: var(--dark-green);
        }
        
        .fare-box {
            text-align: center;
            padding: 10px;
            background: #e8f5e9;
            border-radius: 10px;
            margin: 15px 0;
        }
        
        .fare-box .price {
            font-size: 24px;
            font-weight: bold;
            color: var(--dark-green);
        }
        
        .btn-book {
            width: 100%;
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-book:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
        }
        
        .no-results {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 15px;
        }
        
        .no-results i {
            font-size: 60px;
            color: #ccc;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <!-- Hero Section -->
    <!-- Hero Section - এই অংশটি আপডেট করুন -->
<div class="bus-hero">
    <div class="container">
        <h1 style="color: white;"><i class="fas fa-bus"></i> Bus Tickets</h1>
        <p style="color: rgba(255, 255, 255, 0.9);">Book bus tickets to any destination in Bangladesh</p>
    </div>
</div>
    
    <div class="container">
        <!-- Search Filter -->
        <div class="search-filter">
            <form method="GET" action="">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label><i class="fas fa-map-marker-alt"></i> From</label>
                        <select name="from">
                            <option value="">Select City</option>
                            <?php foreach($cities as $city): ?>
                                <option value="<?php echo $city; ?>" <?php echo $from_filter == $city ? 'selected' : ''; ?>>
                                    <?php echo $city; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label><i class="fas fa-map-marker-alt"></i> To</label>
                        <select name="to">
                            <option value="">Select City</option>
                            <?php foreach($cities as $city): ?>
                                <option value="<?php echo $city; ?>" <?php echo $to_filter == $city ? 'selected' : ''; ?>>
                                    <?php echo $city; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label><i class="fas fa-calendar"></i> Journey Date</label>
                        <input type="date" name="date" value="<?php echo $date_filter; ?>" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i> Search Buses
                        </button>
                        <a href="bus.php" class="btn-reset" style="display: inline-block; text-align: center; text-decoration: none;">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Results Count -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: var(--dark-green);">
                <i class="fas fa-bus"></i> Available Buses 
                (<?php echo mysqli_num_rows($result); ?> found)
            </h3>
            <?php if($from_filter && $to_filter): ?>
                <p style="color: #666;">
                    <i class="fas fa-route"></i> <?php echo $from_filter; ?> → <?php echo $to_filter; ?> | 
                    <i class="fas fa-calendar"></i> <?php echo date('d M, Y', strtotime($date_filter)); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Bus Routes Grid -->
        <?php if(mysqli_num_rows($result) > 0): ?>
            <div class="routes-grid">
                <?php while($route = mysqli_fetch_assoc($result)): ?>
                    <div class="route-card">
                        <div class="route-header">
                            <h3><?php echo $route['bus_name']; ?></h3>
                            <p style="margin: 5px 0 0;"><?php echo $route['bus_type']; ?></p>
                        </div>
                        
                        <div class="route-body">
                            <div class="route-info">
                                <p><i class="fas fa-road"></i> <?php echo $route['from_city']; ?> → <?php echo $route['to_city']; ?></p>
                            </div>
                            
                            <div class="route-time">
                                <div class="time-box">
                                    <div class="time"><?php echo date('h:i A', strtotime($route['departure_time'])); ?></div>
                                    <small>Departure</small>
                                </div>
                                <div><i class="fas fa-long-arrow-alt-right" style="color: var(--primary-green); font-size: 24px;"></i></div>
                                <div class="time-box">
                                    <div class="time"><?php echo date('h:i A', strtotime($route['arrival_time'])); ?></div>
                                    <small>Arrival</small>
                                </div>
                            </div>
                            
                            <div class="fare-box">
                                <div class="price">BDT <?php echo number_format($route['fare']); ?></div>
                                <small>per seat</small>
                            </div>
                            
                            <a href="seat-selection.php?route_id=<?php echo $route['id']; ?>&date=<?php echo $date_filter; ?>" 
                               class="btn-book">
                                <i class="fas fa-chair"></i> Select Seats & Book Now
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-bus"></i>
                <h3>No buses found</h3>
                <p>No buses available for the selected route. Please try different cities or date.</p>
                <a href="bus.php" class="btn btn-green" style="margin-top: 20px;">
                    <i class="fas fa-search"></i> View All Buses
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Popular Routes Section -->
        <div style="margin-top: 60px;">
            <h3 style="color: var(--dark-green); margin-bottom: 20px;">
                <i class="fas fa-fire"></i> Popular Routes
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="bus.php?from=Dhaka&to=Chittagong&date=<?php echo date('Y-m-d'); ?>" 
                   style="background: white; padding: 15px; border-radius: 10px; text-align: center; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <i class="fas fa-bus" style="color: var(--primary-green);"></i>
                    <h4 style="color: #333;">Dhaka → Chittagong</h4>
                    <p style="color: #666;">From BDT 1200</p>
                </a>
                <a href="bus.php?from=Dhaka&to=Sylhet&date=<?php echo date('Y-m-d'); ?>" 
                   style="background: white; padding: 15px; border-radius: 10px; text-align: center; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <i class="fas fa-bus" style="color: var(--primary-green);"></i>
                    <h4 style="color: #333;">Dhaka → Sylhet</h4>
                    <p style="color: #666;">From BDT 800</p>
                </a>
                <a href="bus.php?from=Dhaka&to=Cox%27s%20Bazar&date=<?php echo date('Y-m-d'); ?>" 
                   style="background: white; padding: 15px; border-radius: 10px; text-align: center; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <i class="fas fa-bus" style="color: var(--primary-green);"></i>
                    <h4 style="color: #333;">Dhaka → Cox's Bazar</h4>
                    <p style="color: #666;">From BDT 1500</p>
                </a>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>