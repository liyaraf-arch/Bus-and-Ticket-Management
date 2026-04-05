<?php
// generate-pdf.php - সম্পূর্ণ আপডেটেড ফাইল

require_once 'includes/config.php';

// TCPDF লাইব্রেরি চেক করুন
$tcpdf_path = __DIR__ . '/tcpdf/tcpdf.php';
$tcpdf_vendor_path = __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php';
$use_tcpdf = false;

if(file_exists($tcpdf_path)) {
    require_once($tcpdf_path);
    $use_tcpdf = true;
} elseif(file_exists($tcpdf_vendor_path)) {
    require_once($tcpdf_vendor_path);
    $use_tcpdf = true;
}

if(!isset($_SESSION['user_id'])) {
    die('Unauthorized access');
}

$booking_id = isset($_GET['booking_id']) ? sanitizeInput($_GET['booking_id']) : '';

if(!$booking_id) {
    die('No booking ID provided');
}

// Get booking details
$user_id = $_SESSION['user_id'];
$query = "SELECT b.*, u.name, u.email, u.phone, r.from_city, r.to_city, r.bus_name, r.bus_type, 
          r.departure_time, r.arrival_time, r.fare 
          FROM bookings b 
          JOIN users u ON b.user_id = u.user_id 
          JOIN routes r ON b.route_id = r.id 
          WHERE b.booking_id = '$booking_id' AND b.user_id = '$user_id'";

$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if(!$booking) {
    die('Booking not found');
}

// If TCPDF is not available, use HTML PDF generation
if(!$use_tcpdf || !class_exists('TCPDF')) {
    generateHTMLPDF();
    exit();
}

// Create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Set colors
$green = array(46, 204, 113);
$darkGreen = array(39, 174, 96);
$black = array(0, 0, 0);

// Company Header
$pdf->SetFont('helvetica', 'B', 24);
$pdf->SetTextColor($green[0], $green[1], $green[2]);
$pdf->Cell(0, 15, 'TROTTER.com', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->SetTextColor(128, 128, 128);
$pdf->Cell(0, 8, 'Bus Ticket', 0, 1, 'C');
$pdf->Ln(10);

// Booking ID
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(40, 8, 'Booking ID:', 0, 0);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor($darkGreen[0], $darkGreen[1], $darkGreen[2]);
$pdf->Cell(0, 8, $booking['booking_id'], 0, 1);
$pdf->Ln(5);

// Passenger Details
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetTextColor($green[0], $green[1], $green[2]);
$pdf->Cell(0, 10, 'Passenger Details', 0, 1);
$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(0, 0, 0);

$pdf->Cell(40, 8, 'Name:', 0, 0);
$pdf->Cell(0, 8, $booking['name'], 0, 1);

$pdf->Cell(40, 8, 'Email:', 0, 0);
$pdf->Cell(0, 8, $booking['email'], 0, 1);

$pdf->Cell(40, 8, 'Phone:', 0, 0);
$pdf->Cell(0, 8, $booking['phone'], 0, 1);
$pdf->Ln(5);

// Journey Details
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetTextColor($green[0], $green[1], $green[2]);
$pdf->Cell(0, 10, 'Journey Details', 0, 1);
$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(0, 0, 0);

$pdf->Cell(40, 8, 'Bus:', 0, 0);
$pdf->Cell(0, 8, $booking['bus_name'] . ' (' . $booking['bus_type'] . ')', 0, 1);

$pdf->Cell(40, 8, 'Route:', 0, 0);
$pdf->Cell(0, 8, $booking['from_city'] . ' → ' . $booking['to_city'], 0, 1);

$pdf->Cell(40, 8, 'Date:', 0, 0);
$pdf->Cell(0, 8, date('d M, Y', strtotime($booking['journey_date'])), 0, 1);

$pdf->Cell(40, 8, 'Departure:', 0, 0);
$pdf->Cell(0, 8, date('h:i A', strtotime($booking['departure_time'])), 0, 1);

$pdf->Cell(40, 8, 'Arrival:', 0, 0);
$pdf->Cell(0, 8, date('h:i A', strtotime($booking['arrival_time'])), 0, 1);
$pdf->Ln(5);

// Seat & Payment
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetTextColor($green[0], $green[1], $green[2]);
$pdf->Cell(0, 10, 'Seat & Payment', 0, 1);
$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(0, 0, 0);

$seat_count = count(explode(',', $booking['seat_numbers']));
$service_charge = 20 * $seat_count;
$grand_total = $booking['total_fare'] + $service_charge;

$pdf->Cell(40, 8, 'Seat Nos:', 0, 0);
$pdf->Cell(0, 8, $booking['seat_numbers'], 0, 1);

$pdf->Cell(40, 8, 'Ticket Fare:', 0, 0);
$pdf->Cell(0, 8, 'BDT ' . number_format($booking['total_fare']), 0, 1);

$pdf->Cell(40, 8, 'Service Charge:', 0, 0);
$pdf->Cell(0, 8, 'BDT ' . number_format($service_charge), 0, 1);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor($darkGreen[0], $darkGreen[1], $darkGreen[2]);
$pdf->Cell(40, 8, 'Grand Total:', 0, 0);
$pdf->Cell(0, 8, 'BDT ' . number_format($grand_total), 0, 1);
$pdf->Ln(10);

// QR Code
$qr_data = "Booking ID: " . $booking['booking_id'] . "\n";
$qr_data .= "Name: " . $booking['name'] . "\n";
$qr_data .= "Route: " . $booking['from_city'] . " to " . $booking['to_city'] . "\n";
$qr_data .= "Date: " . $booking['journey_date'] . "\n";
$qr_data .= "Seats: " . $booking['seat_numbers'];

$pdf->write2DBarcode($qr_data, 'QRCODE,L', 80, 180, 50, 50, $style ?? array('border' => 0));

// Important Notes
$pdf->SetY(-50);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetTextColor($green[0], $green[1], $green[2]);
$pdf->Cell(0, 8, 'Important Notes:', 0, 1);
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(128, 128, 128);
$notes = "• Please arrive at least 30 minutes before departure\n";
$notes .= "• Carry a printout or digital copy of this ticket\n";
$notes .= "• Valid ID proof is mandatory for boarding\n";
$notes .= "• Cancellation is available up to 2 hours before departure";
$pdf->MultiCell(0, 5, $notes, 0, 'L');

// Output PDF
$pdf->Output('Ticket_' . $booking_id . '.pdf', 'D'); // 'D' for download, 'I' for inline

// যদি TCPDF না থাকে, তাহলে এই ফাংশন রান করবে
function generateHTMLPDF() {
    global $booking;
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Bus Ticket</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 20px;
            }
            .ticket {
                max-width: 800px;
                margin: 0 auto;
                border: 2px solid #27ae60;
                border-radius: 10px;
                padding: 20px;
            }
            .header {
                text-align: center;
                border-bottom: 2px dashed #27ae60;
                padding-bottom: 15px;
                margin-bottom: 20px;
            }
            .header h1 {
                color: #27ae60;
                margin: 0;
            }
            .section {
                margin-bottom: 20px;
            }
            .section h3 {
                color: #27ae60;
                border-left: 4px solid #27ae60;
                padding-left: 10px;
            }
            .row {
                display: flex;
                margin-bottom: 8px;
            }
            .label {
                width: 120px;
                font-weight: bold;
            }
            .value {
                flex: 1;
            }
            .qr-code {
                text-align: center;
                margin: 20px 0;
            }
            .notes {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                font-size: 12px;
            }
            button {
                background: #27ae60;
                color: white;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                margin-top: 20px;
            }
            @media print {
                button {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="ticket">
            <div class="header">
                <h1>TROTTER.com</h1>
                <p>Bus Ticket</p>
            </div>
            
            <div class="section">
                <h3>Booking Details</h3>
                <div class="row">
                    <div class="label">Booking ID:</div>
                    <div class="value"><?php echo $booking['booking_id']; ?></div>
                </div>
            </div>
            
            <div class="section">
                <h3>Passenger Details</h3>
                <div class="row">
                    <div class="label">Name:</div>
                    <div class="value"><?php echo $booking['name']; ?></div>
                </div>
                <div class="row">
                    <div class="label">Email:</div>
                    <div class="value"><?php echo $booking['email']; ?></div>
                </div>
                <div class="row">
                    <div class="label">Phone:</div>
                    <div class="value"><?php echo $booking['phone']; ?></div>
                </div>
            </div>
            
            <div class="section">
                <h3>Journey Details</h3>
                <div class="row">
                    <div class="label">Bus:</div>
                    <div class="value"><?php echo $booking['bus_name']; ?> (<?php echo $booking['bus_type']; ?>)</div>
                </div>
                <div class="row">
                    <div class="label">Route:</div>
                    <div class="value"><?php echo $booking['from_city']; ?> → <?php echo $booking['to_city']; ?></div>
                </div>
                <div class="row">
                    <div class="label">Date:</div>
                    <div class="value"><?php echo date('d M, Y', strtotime($booking['journey_date'])); ?></div>
                </div>
                <div class="row">
                    <div class="label">Time:</div>
                    <div class="value"><?php echo date('h:i A', strtotime($booking['departure_time'])); ?></div>
                </div>
            </div>
            
            <div class="section">
                <h3>Seat & Payment</h3>
                <div class="row">
                    <div class="label">Seat Nos:</div>
                    <div class="value"><?php echo $booking['seat_numbers']; ?></div>
                </div>
                <?php 
                $seat_count = count(explode(',', $booking['seat_numbers']));
                $service_charge = 20 * $seat_count;
                $grand_total = $booking['total_fare'] + $service_charge;
                ?>
                <div class="row">
                    <div class="label">Ticket Fare:</div>
                    <div class="value">BDT <?php echo number_format($booking['total_fare']); ?></div>
                </div>
                <div class="row">
                    <div class="label">Service Charge:</div>
                    <div class="value">BDT <?php echo number_format($service_charge); ?></div>
                </div>
                <div class="row">
                    <div class="label">Grand Total:</div>
                    <div class="value"><strong>BDT <?php echo number_format($grand_total); ?></strong></div>
                </div>
            </div>
            
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($booking['booking_id']); ?>" alt="QR Code">
                <p>Scan QR code at boarding point</p>
            </div>
            
            <div class="notes">
                <strong>Important Notes:</strong>
                <ul>
                    <li>Please arrive at least 30 minutes before departure</li>
                    <li>Carry a printout or digital copy of this ticket</li>
                    <li>Valid ID proof is mandatory for boarding</li>
                    <li>Cancellation is available up to 2 hours before departure</li>
                </ul>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()"><i class="fas fa-print"></i> Print Ticket</button>
            <button onclick="window.close()">Close</button>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>