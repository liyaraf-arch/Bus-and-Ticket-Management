<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get statistics
$total_buses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM routes"))['count'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings"))['count'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$total_earnings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_fare) as total FROM bookings"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bus Ticket System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100%;
            background: #2c3e50;
            color: white;
            padding-top: 20px;
            z-index: 100;
        }
        
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            color: #2ecc71;
            padding: 0 20px;
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar a:hover {
            background: #34495e;
            border-left-color: #2ecc71;
        }
        
        .sidebar a.active {
            background: #27ae60;
            border-left-color: #fff;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stat-info h3 {
            font-size: 28px;
            color: #2c3e50;
        }
        
        .stat-icon i {
            font-size: 48px;
            color: #2ecc71;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            overflow-x: auto;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .btn-add {
            background: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .btn-view {
            background: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin: 2px;
        }
        
        .btn-edit {
            background: #f39c12;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin: 2px;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin: 2px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            width: 500px;
            max-width: 90%;
            padding: 25px;
            border-radius: 15px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .btn-save {
            background: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .booking-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .status-confirmed {
            background: #28a745;
            color: white;
        }
        
        .status-pending {
            background: #f39c12;
            color: white;
        }
        
        .status-cancelled {
            background: #dc3545;
            color: white;
        }
        
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .sidebar span {
                display: none;
            }
            .main-content {
                margin-left: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <h3><i class="fas fa-bus"></i> Admin Panel</h3>
    
    <a href="dashboard.php" class="sidebar-link">
        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
    </a>
    
    <a href="#" class="sidebar-link" onclick="showSection('add-bus'); return false;">
        <i class="fas fa-plus-circle"></i> <span>Add Bus Route</span>
    </a>
    
    <a href="#" class="sidebar-link" onclick="showSection('manage-buses'); return false;">
        <i class="fas fa-bus"></i> <span>Manage Buses</span>
    </a>
    
    <a href="#" class="sidebar-link" onclick="showSection('bookings'); return false;">
        <i class="fas fa-ticket-alt"></i> <span>Bookings</span>
    </a>
    
    <a href="#" class="sidebar-link" onclick="showSection('users'); return false;">
        <i class="fas fa-users"></i> <span>Users</span>
    </a>
    
    <a href="trending.php" class="sidebar-link">
        <i class="fas fa-image"></i> <span>Trending</span>
    </a>
    
    <!-- ✅ এখানে Events যোগ করুন -->
    <a href="events.php" class="sidebar-link">
        <i class="fas fa-calendar-alt"></i> <span>Events</span>
    </a>
    <a href="refunds.php" class="sidebar-link">
    <i class="fas fa-money-bill-wave"></i> <span>Refunds</span>
</a>
    
    <a href="logout.php" class="sidebar-link">
        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
    </a>
</div>
    
    <div class="main-content">
        <div class="header">
            <h2>Welcome, <?php echo $_SESSION['admin_name']; ?></h2>
            <div class="admin-info">
                <span><i class="fas fa-user-shield"></i> <?php echo $_SESSION['admin_role']; ?></span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?php echo $total_buses; ?></h3>
                    <p>Total Buses</p>
                </div>
                <div class="stat-icon"><i class="fas fa-bus"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?php echo $total_bookings; ?></h3>
                    <p>Total Bookings</p>
                </div>
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Users</p>
                </div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>BDT <?php echo number_format($total_earnings ?: 0); ?></h3>
                    <p>Total Earnings</p>
                </div>
                <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
        
        <!-- Add Bus Section -->
        <div id="add-bus-section" class="table-container" style="display: none;">
            <div class="table-header">
                <h3><i class="fas fa-plus-circle"></i> Add New Bus Route</h3>
            </div>
            <form id="addBusForm" onsubmit="saveBus(event)">
                <div class="form-group">
                    <label>From City</label>
                    <select name="from_city" required>
                        <option value="">Select City</option>
                        <option value="Dhaka">Dhaka</option>
                        <option value="Chittagong">Chittagong</option>
                        <option value="Sylhet">Sylhet</option>
                        <option value="Cox's Bazar">Cox's Bazar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>To City</label>
                    <select name="to_city" required>
                        <option value="">Select City</option>
                        <option value="Dhaka">Dhaka</option>
                        <option value="Chittagong">Chittagong</option>
                        <option value="Sylhet">Sylhet</option>
                        <option value="Cox's Bazar">Cox's Bazar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Bus Name</label>
                    <input type="text" name="bus_name" required>
                </div>
                <div class="form-group">
                    <label>Bus Type</label>
                    <select name="bus_type" required>
                        <option value="AC">AC</option>
                        <option value="Non-AC">Non-AC</option>
                        <option value="AC Business">AC Business</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Departure Time</label>
                    <input type="time" name="departure_time" required>
                </div>
                <div class="form-group">
                    <label>Arrival Time</label>
                    <input type="time" name="arrival_time" required>
                </div>
                <div class="form-group">
                    <label>Fare (BDT)</label>
                    <input type="number" name="fare" required>
                </div>
                <div class="form-group">
                    <label>Total Seats</label>
                    <input type="number" name="total_seats" value="40" required>
                </div>
                <button type="submit" class="btn-save">Save Bus Route</button>
            </form>
        </div>
        
        <!-- Manage Buses Section -->
        <div id="manage-buses-section" class="table-container" style="display: none;">
            <div class="table-header">
                <h3><i class="fas fa-bus"></i> Manage Bus Routes</h3>
                <button class="btn-add" onclick="showSection('add-bus')"><i class="fas fa-plus"></i> Add New</button>
            </div>
            <table>
                <thead>
                    <tr><th>ID</th><th>From</th><th>To</th><th>Bus Name</th><th>Type</th><th>Departure</th><th>Arrival</th><th>Fare</th><th>Actions</th></tr>
                </thead>
                <tbody id="busesTableBody">
                    <?php
                    $buses_query = "SELECT * FROM routes ORDER BY id DESC";
                    $buses_result = mysqli_query($conn, $buses_query);
                    while($bus = mysqli_fetch_assoc($buses_result)):
                    ?>
                    <tr id="bus-row-<?php echo $bus['id']; ?>">
                        <td><?php echo $bus['id']; ?></td>
                        <td><?php echo $bus['from_city']; ?></td>
                        <td><?php echo $bus['to_city']; ?></td>
                        <td><?php echo $bus['bus_name']; ?></td>
                        <td><?php echo $bus['bus_type']; ?></td>
                        <td><?php echo !empty($bus['departure_time']) ? date('h:i A', strtotime($bus['departure_time'])) : '—'; ?></td>
                        <td><?php echo !empty($bus['arrival_time']) ? date('h:i A', strtotime($bus['arrival_time'])) : '—'; ?></td>
                        <td>BDT <?php echo number_format($bus['fare']); ?></td>
                        <td>
                            <button class="btn-edit" onclick="editBus(<?php echo $bus['id']; ?>)"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn-delete" onclick="deleteBus(<?php echo $bus['id']; ?>, '<?php echo addslashes($bus['bus_name']); ?>')"><i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Bookings Section -->
        <div id="bookings-section" class="table-container" style="display: none;">
            <div class="table-header">
                <h3><i class="fas fa-ticket-alt"></i> All Bookings</h3>
                <select id="bookingFilter" onchange="filterBookings()" style="padding: 8px; border-radius: 5px;">
                    <option value="all">All Bookings</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th><th>Passenger</th><th>Route</th><th>Bus</th><th>Date</th><th>Seats</th><th>Amount</th><th>Payment</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bookingsTableBody">
                    <?php
                    $bookings_query = "SELECT b.*, u.name as passenger_name, u.email, u.phone, r.from_city, r.to_city, r.bus_name, r.bus_type, r.departure_time, r.arrival_time FROM bookings b JOIN users u ON b.user_id = u.user_id JOIN routes r ON b.route_id = r.id ORDER BY b.id DESC";
                    $bookings_result = mysqli_query($conn, $bookings_query);
                    while($booking = mysqli_fetch_assoc($bookings_result)):
                        $status_class = $booking['status'] == 'confirmed' ? 'status-confirmed' : ($booking['status'] == 'cancelled' ? 'status-cancelled' : 'status-pending');
                        $payment_display = $booking['payment_method'] == 'bkash' ? '<img src="../assets/images/bkash-logo.png" style="width: 20px;"> bKash' : ($booking['payment_method'] == 'nagad' ? '<img src="../assets/images/nagad-logo.png" style="width: 20px;"> Nagad' : ($booking['payment_method'] == 'card' ? '<i class="fas fa-credit-card"></i> Card' : '<i class="fas fa-clock"></i> Pending'));
                        $departure_time_display = !empty($booking['departure_time']) ? date('h:i A', strtotime($booking['departure_time'])) : '—';
                    ?>
                    <tr id="booking-row-<?php echo $booking['id']; ?>" data-status="<?php echo $booking['status']; ?>">
                        <td><strong><?php echo $booking['booking_id']; ?></strong><br><small><?php echo date('d M', strtotime($booking['booking_date'])); ?></small></td>
                        <td><strong><?php echo $booking['passenger_name']; ?></strong><br><small><?php echo $booking['phone']; ?></small></td>
                        <td><?php echo $booking['from_city']; ?> → <?php echo $booking['to_city']; ?></td>
                        <td><?php echo $booking['bus_name']; ?><br><small><?php echo $booking['bus_type']; ?></small></td>
                        <td><?php echo date('d M, Y', strtotime($booking['journey_date'])); ?><br><small><?php echo $departure_time_display; ?></small></td>
                        <td style="text-align: center;"><strong><?php echo $booking['seat_numbers']; ?></strong></td>
                        <td>BDT <?php echo number_format($booking['total_fare']); ?></td>
                        <td><?php echo $payment_display; ?></td>
                        <td><span class="booking-status <?php echo $status_class; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                        <td>
                            <button class="btn-view" onclick="viewBooking('<?php echo $booking['booking_id']; ?>')"><i class="fas fa-eye"></i> View</button>
                            <button class="btn-edit" onclick="editBooking('<?php echo $booking['booking_id']; ?>')"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn-delete" onclick="deleteBookingItem('<?php echo $booking['booking_id']; ?>', '<?php echo addslashes($booking['passenger_name']); ?>')"><i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Users Section -->
        <div id="users-section" class="table-container" style="display: none;">
            <div class="table-header">
                <h3><i class="fas fa-users"></i> All Users</h3>
            </div>
            <table>
                <thead><tr><th>User ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Joined Date</th><th>Action</th></tr></thead>
                <tbody>
                    <?php
                    $users_query = "SELECT * FROM users ORDER BY id DESC";
                    $users_result = mysqli_query($conn, $users_query);
                    while($user = mysqli_fetch_assoc($users_result)):
                    ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['phone']; ?></td>
                        <td><?php echo date('d M, Y', strtotime($user['created_at'])); ?></td>
                        <td><button class="btn-delete" onclick="deleteUser('<?php echo $user['user_id']; ?>')"><i class="fas fa-trash"></i> Delete</button></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Edit Bus Modal -->
    <div id="editBusModal" class="modal">
        <div class="modal-content">
            <h3><i class="fas fa-edit"></i> Edit Bus Route</h3>
            <form id="editBusForm" onsubmit="updateBus(event)">
                <input type="hidden" id="edit_bus_id" name="bus_id">
                <div class="form-group"><label>From City</label><select id="edit_from_city" name="from_city" required><option value="Dhaka">Dhaka</option><option value="Chittagong">Chittagong</option><option value="Sylhet">Sylhet</option><option value="Cox's Bazar">Cox's Bazar</option></select></div>
                <div class="form-group"><label>To City</label><select id="edit_to_city" name="to_city" required><option value="Dhaka">Dhaka</option><option value="Chittagong">Chittagong</option><option value="Sylhet">Sylhet</option><option value="Cox's Bazar">Cox's Bazar</option></select></div>
                <div class="form-group"><label>Bus Name</label><input type="text" id="edit_bus_name" name="bus_name" required></div>
                <div class="form-group"><label>Bus Type</label><select id="edit_bus_type" name="bus_type" required><option value="AC">AC</option><option value="Non-AC">Non-AC</option><option value="AC Business">AC Business</option></select></div>
                <div class="form-group"><label>Departure Time</label><input type="time" id="edit_departure_time" name="departure_time" required></div>
                <div class="form-group"><label>Arrival Time</label><input type="time" id="edit_arrival_time" name="arrival_time" required></div>
                <div class="form-group"><label>Fare</label><input type="number" id="edit_fare" name="fare" required></div>
                <div class="form-group"><label>Total Seats</label><input type="number" id="edit_total_seats" name="total_seats" required></div>
                <button type="submit" class="btn-save">Update Bus</button>
                <button type="button" onclick="closeModal('editBusModal')" style="margin-left: 10px;">Cancel</button>
            </form>
        </div>
    </div>
    
    <!-- Booking View/Edit Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <h3 id="modalTitle">Booking Details</h3>
            <div id="bookingModalContent"></div>
            <div style="margin-top: 20px; text-align: center;">
                <button onclick="closeModal('bookingModal')" class="btn-save" style="background: #666;">Close</button>
            </div>
        </div>
    </div>
    
    <script>
        function showSection(section) {
            document.querySelectorAll('.table-container').forEach(el => el.style.display = 'none');
            document.getElementById(section + '-section').style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.style.background = type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db';
            toast.style.color = 'white';
            toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i> ${message}`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
        
        // Bus Functions
        function saveBus(event) {
            event.preventDefault();
            fetch('save-bus.php', { method: 'POST', body: new FormData(event.target) })
                .then(r => r.json()).then(d => { if(d.success) { showToast('Bus added!', 'success'); location.reload(); } else { showToast(d.message, 'error'); } });
        }
        
        function editBus(id) {
            fetch(`get-bus.php?id=${id}`).then(r => r.json()).then(data => {
                document.getElementById('edit_bus_id').value = data.id;
                document.getElementById('edit_from_city').value = data.from_city;
                document.getElementById('edit_to_city').value = data.to_city;
                document.getElementById('edit_bus_name').value = data.bus_name;
                document.getElementById('edit_bus_type').value = data.bus_type;
                document.getElementById('edit_departure_time').value = data.departure_time;
                document.getElementById('edit_arrival_time').value = data.arrival_time;
                document.getElementById('edit_fare').value = data.fare;
                document.getElementById('edit_total_seats').value = data.total_seats;
                document.getElementById('editBusModal').style.display = 'flex';
            });
        }
        
        function updateBus(event) {
            event.preventDefault();
            fetch('update-bus.php', { method: 'POST', body: new FormData(event.target) })
                .then(r => r.json()).then(d => { 
                    if(d.success) { showToast('Bus updated!', 'success'); location.reload(); } else { showToast(d.message, 'error'); } });
        }
        
        function deleteBus(id, name) {
            if(confirm(`Delete "${name}"?`)) {
                fetch(`delete-bus.php?id=${id}`).then(r => r.json()).then(d => {
                    if(d.success) { showToast('Bus deleted!', 'success'); document.getElementById(`bus-row-${id}`).remove(); }
                    else { showToast(d.message, 'error'); }
                });
            }
        }
        
        // Booking Functions
        function viewBooking(id) {
            showToast('Loading...', 'info');
            fetch(`get-booking-details.php?booking_id=${id}`).then(r => r.json()).then(data => {
                if(data.success) {
                    const b = data.booking;
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-eye"></i> Booking Details';
                    document.getElementById('bookingModalContent').innerHTML = `
                        <div style="background:#f8f9fa;padding:15px;border-radius:10px;margin-bottom:15px;">
                            <h4>Booking Information</h4>
                            <p><strong>Booking ID:</strong> ${b.booking_id}</p>
                            <p><strong>Status:</strong> <span class="booking-status status-${b.status}">${b.status.toUpperCase()}</span></p>
                            <p><strong>Booking Date:</strong> ${b.booking_date}</p>
                        </div>
                        <div style="background:#f8f9fa;padding:15px;border-radius:10px;margin-bottom:15px;">
                            <h4>Passenger Details</h4>
                            <p><strong>Name:</strong> ${b.passenger_name}</p>
                            <p><strong>Email:</strong> ${b.email}</p>
                            <p><strong>Phone:</strong> ${b.phone}</p>
                        </div>
                        <div style="background:#f8f9fa;padding:15px;border-radius:10px;margin-bottom:15px;">
                            <h4>Journey Details</h4>
                            <p><strong>Bus:</strong> ${b.bus_name} (${b.bus_type})</p>
                            <p><strong>Route:</strong> ${b.from_city} → ${b.to_city}</p>
                            <p><strong>Date:</strong> ${b.journey_date}</p>
                            <p><strong>Departure:</strong> ${b.departure_time || '—'}</p>
                            <p><strong>Arrival:</strong> ${b.arrival_time || '—'}</p>
                            <p><strong>Seats:</strong> ${b.seat_numbers}</p>
                        </div>
                        <div style="background:#f8f9fa;padding:15px;border-radius:10px;">
                            <h4>Payment Details</h4>
                            <p><strong>Method:</strong> ${b.payment_method || 'Pending'}</p>
                            <p><strong>Ticket Fare:</strong> BDT ${b.total_fare}</p>
                            <p><strong>Service Charge:</strong> BDT ${b.service_charge}</p>
                            <p><strong>Grand Total:</strong> BDT ${b.grand_total}</p>
                        </div>
                    `;
                    document.getElementById('bookingModal').style.display = 'flex';
                } else showToast(data.message, 'error');
            });
        }
        
        function editBooking(id) {
            showToast('Loading...', 'info');
            fetch(`get-booking-details.php?booking_id=${id}`).then(r => r.json()).then(data => {
                if(data.success) {
                    const b = data.booking;
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Booking';
                    document.getElementById('bookingModalContent').innerHTML = `
                        <form id="editBookingForm" onsubmit="saveBookingChanges('${b.booking_id}')">
                            <div style="background:#f8f9fa;padding:15px;border-radius:10px;margin-bottom:15px;">
                                <h4>Booking Status</h4>
                                <select name="status" id="edit_status" class="form-control" style="width:100%;padding:8px;">
                                    <option value="confirmed" ${b.status == 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                    <option value="pending" ${b.status == 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="cancelled" ${b.status == 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                </select>
                            </div>
                            <div style="background:#f8f9fa;padding:15px;border-radius:10px;margin-bottom:15px;">
                                <h4>Passenger Details</h4>
                                <div class="form-group"><label>Name</label><input type="text" id="edit_name" value="${b.passenger_name}" class="form-control"></div>
                                <div class="form-group"><label>Email</label><input type="email" id="edit_email" value="${b.email}" class="form-control"></div>
                                <div class="form-group"><label>Phone</label><input type="text" id="edit_phone" value="${b.phone}" class="form-control"></div>
                            </div>
                            <div style="background:#f8f9fa;padding:15px;border-radius:10px;margin-bottom:15px;">
                                <h4>Payment Method</h4>
                                <select id="edit_payment_method" class="form-control" style="width:100%;padding:8px;">
                                    <option value="pending" ${b.payment_method == 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="bkash" ${b.payment_method == 'bkash' ? 'selected' : ''}>bKash</option>
                                    <option value="nagad" ${b.payment_method == 'nagad' ? 'selected' : ''}>Nagad</option>
                                    <option value="card" ${b.payment_method == 'card' ? 'selected' : ''}>Credit Card</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-save">Save Changes</button>
                        </form>
                    `;
                    document.getElementById('bookingModal').style.display = 'flex';
                } else showToast(data.message, 'error');
            });
        }
        
        function saveBookingChanges(bookingId) {
            event.preventDefault();
            const status = document.getElementById('edit_status')?.value;
            const paymentMethod = document.getElementById('edit_payment_method')?.value;
            const name = document.getElementById('edit_name')?.value;
            const email = document.getElementById('edit_email')?.value;
            const phone = document.getElementById('edit_phone')?.value;
            
            fetch('update-booking.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `booking_id=${bookingId}&status=${status}&payment_method=${paymentMethod}&name=${name}&email=${email}&phone=${phone}`
            }).then(r => r.json()).then(data => {
                if(data.success) {
                    showToast('Booking updated!', 'success');
                    closeModal('bookingModal');
                    setTimeout(() => location.reload(), 1000);
                } else showToast(data.message, 'error');
            });
            return false;
        }
        
        function deleteBookingItem(id, name) {
            if(confirm(`Delete booking for ${name}?`)) {
                fetch(`delete-booking.php?booking_id=${id}`).then(r => r.json()).then(data => {
                    if(data.success) {
                        showToast('Booking deleted!', 'success');
                        document.getElementById(`booking-row-${data.id}`).remove();
                    } else showToast(data.message, 'error');
                });
            }
        }
        
        function filterBookings() {
            const filter = document.getElementById('bookingFilter').value;
            document.querySelectorAll('#bookingsTableBody tr').forEach(row => {
                row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
            });
        }
        
        function deleteUser(id) {
            if(confirm('Delete user?')) {
                fetch(`delete-user.php?user_id=${id}`).then(r => r.json()).then(d => { if(d.success) location.reload(); else alert(d.message); });
            }
        }
        
        showSection('manage-buses');
    </script>
</body>
</html>