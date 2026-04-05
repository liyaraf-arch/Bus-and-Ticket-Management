<?php
// pages/search.php - Search buses with round trip support

require_once '../includes/config.php';

$page_title = __('search_buses');

// Get search parameters
$from = isset($_GET['from']) ? sanitizeInput($_GET['from']) : '';
$to = isset($_GET['to']) ? sanitizeInput($_GET['to']) : '';
$date = isset($_GET['date']) ? sanitizeInput($_GET['date']) : '';
$return_date = isset($_GET['return_date']) ? sanitizeInput($_GET['return_date']) : '';
$trip_type = isset($_GET['trip_type']) ? $_GET['trip_type'] : 'oneway';

// Store in session
$_SESSION['trip_type'] = $trip_type;
$_SESSION['return_date'] = $return_date;
$_SESSION['from_city'] = $from;
$_SESSION['to_city'] = $to;

// Search buses for onward journey
$buses = [];
if($from && $to && $date) {
    $query = "SELECT * FROM routes WHERE from_city = '$from' AND to_city = '$to'";
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_assoc($result)) {
        $seats = getAvailableSeats($row['id'], $date);
        $row['available_seats'] = $seats['available'];
        $row['total_seats'] = $seats['total'];
        
        // Set fare based on trip type
        if($trip_type == 'round' && $row['is_round_trip'] == 1) {
            $row['display_fare'] = $row['round_trip_fare'];
            $row['fare_type'] = 'round';
        } else {
            $row['display_fare'] = $row['fare'];
            $row['fare_type'] = 'oneway';
        }
        
        $buses[] = $row;
    }
}

// Search return journey buses for round trip
$return_buses = [];
if($trip_type == 'round' && $return_date && $to && $from) {
    $return_query = "SELECT * FROM routes WHERE from_city = '$to' AND to_city = '$from'";
    $return_result = mysqli_query($conn, $return_query);
    
    while($row = mysqli_fetch_assoc($return_result)) {
        $seats = getAvailableSeats($row['id'], $return_date);
        $row['available_seats'] = $seats['available'];
        $row['total_seats'] = $seats['total'];
        
        if($row['is_round_trip'] == 1) {
            $row['display_fare'] = $row['round_trip_fare'];
        } else {
            $row['display_fare'] = $row['fare'];
        }
        
        $return_buses[] = $row;
    }
}
?>

<!-- Show both onward and return buses for round trip -->
<div style="margin: 30px 0;">
    
    <!-- Onward Journey Buses -->
    <h2 style="color: var(--dark-green); margin-bottom: 20px;">
        <i class="fas fa-arrow-right"></i> Onward Journey (<?php echo $from; ?> → <?php echo $to; ?>)
        <small style="font-size: 14px;"><?php echo date('d M, Y', strtotime($date)); ?></small>
    </h2>
    
    <?php if(empty($buses)): ?>
        <div style="text-align: center; padding: 30px; background: white; border-radius: 10px;">
            <p>No buses found for onward journey.</p>
        </div>
    <?php else: ?>
        <div class="routes-grid">
            <?php foreach($buses as $bus): ?>
            <div class="route-card">
                <!-- Bus card content -->
                <div class="route-header">
                    <h3><?php echo $bus['bus_name']; ?></h3>
                    <p><?php echo $bus['bus_type']; ?></p>
                </div>
                <div class="route-body">
                    <div class="route-time">
                        <div class="time-box">
                            <div class="time"><?php echo date('h:i A', strtotime($bus['departure_time'])); ?></div>
                            <small>Departure</small>
                        </div>
                        <div><i class="fas fa-long-arrow-alt-right"></i></div>
                        <div class="time-box">
                            <div class="time"><?php echo date('h:i A', strtotime($bus['arrival_time'])); ?></div>
                            <small>Arrival</small>
                        </div>
                    </div>
                    <div class="fare-box">
                        <div class="price">
                            BDT <?php echo number_format($bus['display_fare']); ?>
                            <?php if($trip_type == 'round'): ?>
                                <small>(Round Trip)</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="seat-selection.php?route_id=<?php echo $bus['id']; ?>&date=<?php echo $date; ?>&trip_type=onward" class="btn-book">
                        <i class="fas fa-chair"></i> Select Seats
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Return Journey Buses (for round trip) -->
    <?php if($trip_type == 'round'): ?>
        <h2 style="color: var(--dark-green); margin: 40px 0 20px 0;">
            <i class="fas fa-arrow-left"></i> Return Journey (<?php echo $to; ?> → <?php echo $from; ?>)
            <small style="font-size: 14px;"><?php echo date('d M, Y', strtotime($return_date)); ?></small>
        </h2>
        
        <?php if(empty($return_buses)): ?>
            <div style="text-align: center; padding: 30px; background: white; border-radius: 10px;">
                <p>No buses found for return journey.</p>
            </div>
        <?php else: ?>
            <div class="routes-grid">
                <?php foreach($return_buses as $bus): ?>
                <div class="route-card">
                    <div class="route-header">
                        <h3><?php echo $bus['bus_name']; ?></h3>
                        <p><?php echo $bus['bus_type']; ?></p>
                    </div>
                    <div class="route-body">
                        <div class="route-time">
                            <div class="time-box">
                                <div class="time"><?php echo date('h:i A', strtotime($bus['departure_time'])); ?></div>
                                <small>Departure</small>
                            </div>
                            <div><i class="fas fa-long-arrow-alt-right"></i></div>
                            <div class="time-box">
                                <div class="time"><?php echo date('h:i A', strtotime($bus['arrival_time'])); ?></div>
                                <small>Arrival</small>
                            </div>
                        </div>
                        <div class="fare-box">
                            <div class="price">BDT <?php echo number_format($bus['display_fare']); ?></div>
                        </div>
                        <a href="seat-selection.php?route_id=<?php echo $bus['id']; ?>&date=<?php echo $return_date; ?>&trip_type=return" class="btn-book">
                            <i class="fas fa-chair"></i> Select Seats
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
</div>