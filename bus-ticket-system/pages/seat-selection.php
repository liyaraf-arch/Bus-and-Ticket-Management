<?php
require_once '../includes/config.php';

if(!isLoggedIn()) {
    $_SESSION['error'] = 'Please login to select seats';
    redirect('login.php');
}

$page_title = 'Select Seats';

$route_id = isset($_GET['route_id']) ? (int)$_GET['route_id'] : 0;
$journey_date = isset($_GET['date']) ? sanitizeInput($_GET['date']) : '';

if(!$route_id || !$journey_date) {
    $_SESSION['error'] = 'Invalid request';
    redirect('index.php');
}

// Get route details
$route_query = "SELECT * FROM routes WHERE id = $route_id";
$route_result = mysqli_query($conn, $route_query);
$route = mysqli_fetch_assoc($route_result);

if(!$route) {
    $_SESSION['error'] = 'Route not found';
    redirect('index.php');
}

// Get seat availability
$seats = getAvailableSeats($route_id, $journey_date);
$booked_seats = $seats['booked'];

// Calculate total seats from database
$total_seats = $route['total_seats'];
$seats_per_row = 4; // 4 seats per row (2 left + 2 right)
$rows_needed = ceil($total_seats / $seats_per_row);

// Generate rows (A, B, C, D, E, F, G, H, I, J, K...)
$rows = [];
$alphabet = range('A', 'Z');
for($i = 0; $i < $rows_needed; $i++) {
    $rows[] = $alphabet[$i];
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
        .seat-layout {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin: 30px 0;
        }
        
        .bus-container {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 30px;
            position: relative;
            overflow-x: auto;
        }
        
        .driver-area {
            text-align: center;
            margin-bottom: 30px;
            padding: 15px;
            background: #2c3e50;
            color: white;
            border-radius: 10px;
            display: inline-block;
            width: 100%;
        }
        
        .driver-area i {
            font-size: 24px;
            margin-right: 10px;
        }
        
        .seat-grid {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }
        
        .seat-column {
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .column-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 5px;
            background: #e9ecef;
            border-radius: 5px;
            color: #495057;
        }
        
        .seat-row {
            display: flex;
            margin-bottom: 12px;
            gap: 12px;
        }
        
        .seat {
            width: 70px;
            height: 70px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            font-weight: bold;
            position: relative;
        }
        
        .seat-number {
            font-size: 16px;
            font-weight: bold;
        }
        
        .seat-type {
            font-size: 10px;
            color: #666;
        }
        
        .seat.available:hover {
            background: var(--light-green);
            transform: scale(1.05);
            border-color: var(--dark-green);
        }
        
        .seat.booked {
            background: #e74c3c;
            border-color: #c0392b;
            cursor: not-allowed;
            color: white;
            opacity: 0.7;
        }
        
        .seat.selected {
            background: var(--primary-green);
            color: white;
            border-color: var(--dark-green);
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .seat.ladies {
            background: #ffb6c1;
            border-color: #ff69b4;
        }
        
        .legend {
            display: flex;
            gap: 25px;
            justify-content: center;
            margin: 25px 0;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .legend-color {
            width: 30px;
            height: 30px;
            border-radius: 8px;
        }
        
        .summary-box {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            border-radius: 15px;
            padding: 25px;
            margin-top: 25px;
            color: white;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            text-align: center;
        }
        
        .summary-label {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .summary-value {
            font-size: 24px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .button-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn-clear-selection {
            background: #f8f9fa;
            color: #e74c3c;
            border: 2px solid #e74c3c;
            padding: 14px 35px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-clear-selection:hover {
            background: #e74c3c;
            color: white;
            transform: translateY(-3px);
        }
        
        .btn-continue-payment {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            border: none;
            padding: 14px 45px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .btn-continue-payment:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(46, 204, 113, 0.4);
        }
        
        .btn-continue-payment:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        @media (max-width: 768px) {
            .seat {
                width: 55px;
                height: 55px;
            }
            .seat-row {
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <!-- Trip Summary -->
        <div style="background: white; padding: 20px; border-radius: 15px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <div>
                    <h3 style="color: var(--dark-green);"><?php echo $route['bus_name']; ?></h3>
                    <p style="color: #666;">
                        <i class="fas fa-bus"></i> <?php echo $route['bus_type']; ?> • 
                        <i class="fas fa-chair"></i> <?php echo $route['total_seats']; ?> seats
                    </p>
                </div>
                <div style="text-align: right;">
                    <h2 style="color: var(--dark-green);"><?php echo $route['from_city']; ?> → <?php echo $route['to_city']; ?></h2>
                    <p style="color: #666;">
                        <i class="fas fa-calendar"></i> <?php echo formatDate($journey_date); ?> • 
                        <i class="fas fa-clock"></i> <?php echo formatTime($route['departure_time']); ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="seat-layout">
            <h2 style="color: var(--dark-green); text-align: center; margin-bottom: 20px;">
                <i class="fas fa-chair"></i> Select Your Seats (Total: <?php echo $total_seats; ?> seats)
            </h2>
            
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background: white; border: 2px solid var(--border-color);"></div>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: var(--primary-green);"></div>
                    <span>Selected</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #e74c3c;"></div>
                    <span>Booked</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #ffb6c1; border: 1px solid #ff69b4;"></div>
                    <span>Ladies</span>
                </div>
            </div>
            
            <div class="bus-container">
                <div class="driver-area">
                    <i class="fas fa-steering-wheel"></i> DRIVER
                </div>
                
                <div class="seat-grid">
                    <!-- Left Side Seats -->
                    <div class="seat-column">
                        <div class="column-title">LEFT SIDE</div>
                        <?php
                        $seat_counter = 1;
                        foreach($rows as $row):
                            if($seat_counter > $total_seats) break;
                        ?>
                        <div class="seat-row">
                            <?php for($col = 1; $col <= 2; $col++): 
                                if($seat_counter > $total_seats) break;
                                $seat_number = $row . '-' . $col;
                                $is_booked = in_array($seat_counter, $booked_seats);
                                $is_ladies = in_array($row, ['C', 'F']) && $col == 1;
                                $seat_class = '';
                                if($is_booked) {
                                    $seat_class = 'booked';
                                } elseif($is_ladies) {
                                    $seat_class = 'ladies';
                                } else {
                                    $seat_class = 'available';
                                }
                            ?>
                                <div class="seat <?php echo $seat_class; ?>" 
                                     data-seat-id="<?php echo $seat_counter; ?>"
                                     data-seat-number="<?php echo $seat_number; ?>"
                                     onclick="<?php echo $is_booked ? '' : 'selectSeat(this, ' . $route['fare'] . ')'; ?>"
                                     <?php echo $is_booked ? 'style="cursor: not-allowed;"' : ''; ?>>
                                    <div class="seat-number"><?php echo $seat_number; ?></div>
                                    <div class="seat-type"><?php echo $is_ladies ? '👩' : ''; ?></div>
                                </div>
                            <?php 
                                $seat_counter++;
                                endfor; 
                            ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Right Side Seats -->
                    <div class="seat-column">
                        <div class="column-title">RIGHT SIDE</div>
                        <?php
                        // Reset counter for right side
                        $seat_counter = 1;
                        $right_side_counter = 1;
                        foreach($rows as $row):
                            if($right_side_counter > $total_seats) break;
                        ?>
                        <div class="seat-row">
                            <?php for($col = 3; $col <= 4; $col++): 
                                if($right_side_counter > $total_seats) break;
                                $seat_number = $row . '-' . $col;
                                // Calculate actual seat ID for right side
                                $seat_id = (($right_side_counter - 1) * 4) + $col;
                                $is_booked = in_array($seat_id, $booked_seats);
                                $is_ladies = in_array($row, ['C', 'F']) && $col == 3;
                                $seat_class = '';
                                if($is_booked) {
                                    $seat_class = 'booked';
                                } elseif($is_ladies) {
                                    $seat_class = 'ladies';
                                } else {
                                    $seat_class = 'available';
                                }
                            ?>
                                <div class="seat <?php echo $seat_class; ?>" 
                                     data-seat-id="<?php echo $seat_id; ?>"
                                     data-seat-number="<?php echo $seat_number; ?>"
                                     onclick="<?php echo $is_booked ? '' : 'selectSeat(this, ' . $route['fare'] . ')'; ?>"
                                     <?php echo $is_booked ? 'style="cursor: not-allowed;"' : ''; ?>>
                                    <div class="seat-number"><?php echo $seat_number; ?></div>
                                    <div class="seat-type"><?php echo $is_ladies ? '👩' : ''; ?></div>
                                </div>
                            <?php 
                                $right_side_counter++;
                                endfor; 
                            ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Summary Box -->
            <div class="summary-box" id="summaryBox" style="display: none;">
                <div class="summary-grid">
                    <div>
                        <div class="summary-label">Selected Seats</div>
                        <div class="summary-value" id="selectedSeats">-</div>
                    </div>
                    <div>
                        <div class="summary-label">Total Fare</div>
                        <div class="summary-value" id="totalFare">BDT 0</div>
                    </div>
                    <div>
                        <div class="summary-label">Service Charge</div>
                        <div class="summary-value" id="serviceCharge">BDT 0</div>
                    </div>
                    <div>
                        <div class="summary-label">Grand Total</div>
                        <div class="summary-value" id="grandTotal">BDT 0</div>
                    </div>
                </div>
            </div>
            
            <form id="bookingForm" action="booking-confirmation.php" method="POST">
                <input type="hidden" name="route_id" value="<?php echo $route_id; ?>">
                <input type="hidden" name="journey_date" value="<?php echo $journey_date; ?>">
                <input type="hidden" name="seat_numbers" id="seatNumbers">
                <input type="hidden" name="total_fare" id="totalFareInput">
                
                <div class="button-container">
                    <button type="button" class="btn-clear-selection" onclick="clearSelection()">
                        <i class="fas fa-undo-alt"></i> Clear Selection
                    </button>
                    <button type="submit" class="btn-continue-payment" onclick="return validateSeats()" id="continueBtn">
                        <i class="fas fa-arrow-right"></i> Continue to Payment
                        <i class="fas fa-credit-card"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        let selectedSeats = [];
        let farePerSeat = <?php echo $route['fare']; ?>;
        
        function selectSeat(element, fare) {
            if(element.classList.contains('selected')) {
                element.classList.remove('selected');
                selectedSeats = selectedSeats.filter(s => s.id != element.dataset.seatId);
            } else {
                element.classList.add('selected');
                selectedSeats.push({
                    id: element.dataset.seatId,
                    number: element.dataset.seatNumber
                });
            }
            updateSummary();
        }
        
        function updateSummary() {
            const summaryBox = document.getElementById('summaryBox');
            const continueBtn = document.getElementById('continueBtn');
            
            if(selectedSeats.length > 0) {
                summaryBox.style.display = 'block';
                if(continueBtn) {
                    continueBtn.disabled = false;
                    continueBtn.style.opacity = '1';
                }
                
                const seatNumbers = selectedSeats.map(s => s.number).join(', ');
                document.getElementById('selectedSeats').textContent = seatNumbers;
                
                const totalFare = selectedSeats.length * farePerSeat;
                const serviceCharge = 20 * selectedSeats.length;
                const grandTotal = totalFare + serviceCharge;
                
                document.getElementById('totalFare').textContent = 'BDT ' + totalFare.toLocaleString();
                document.getElementById('serviceCharge').textContent = 'BDT ' + serviceCharge.toLocaleString();
                document.getElementById('grandTotal').textContent = 'BDT ' + grandTotal.toLocaleString();
                
                document.getElementById('seatNumbers').value = selectedSeats.map(s => s.id).join(',');
                document.getElementById('totalFareInput').value = totalFare;
            } else {
                summaryBox.style.display = 'none';
                if(continueBtn) {
                    continueBtn.disabled = true;
                    continueBtn.style.opacity = '0.6';
                }
            }
        }
        
        function clearSelection() {
            document.querySelectorAll('.seat.selected').forEach(seat => {
                seat.classList.remove('selected');
            });
            selectedSeats = [];
            updateSummary();
        }
        
        function validateSeats() {
            if(selectedSeats.length === 0) {
                alert('⚠️ Please select at least one seat!');
                return false;
            }
            
            const totalFare = selectedSeats.length * farePerSeat;
            const serviceCharge = 20 * selectedSeats.length;
            const grandTotal = totalFare + serviceCharge;
            
            return confirm(`✅ Confirm Booking\n\n📌 Seats: ${selectedSeats.map(s => s.number).join(', ')}\n💰 Ticket Fare: BDT ${totalFare.toLocaleString()}\n🛎️ Service Charge: BDT ${serviceCharge.toLocaleString()}\n💵 Grand Total: BDT ${grandTotal.toLocaleString()}\n\nClick OK to proceed.`);
        }
        
        // Initialize button state
        document.addEventListener('DOMContentLoaded', function() {
            const continueBtn = document.getElementById('continueBtn');
            if(continueBtn) {
                continueBtn.disabled = true;
                continueBtn.style.opacity = '0.6';
            }
        });
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>