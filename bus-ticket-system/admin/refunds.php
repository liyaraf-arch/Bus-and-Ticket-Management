<?php
// admin/refunds.php - Admin Refund Management
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle approve/reject
if(isset($_GET['approve_id'])) {
    $id = (int)$_GET['approve_id'];
    $update = "UPDATE refund_requests SET status = 'approved', processed_date = NOW() WHERE id = $id";
    mysqli_query($conn, $update);
    header("Location: refunds.php?msg=approved");
    exit();
}

if(isset($_GET['reject_id'])) {
    $id = (int)$_GET['reject_id'];
    $update = "UPDATE refund_requests SET status = 'rejected', processed_date = NOW() WHERE id = $id";
    mysqli_query($conn, $update);
    header("Location: refunds.php?msg=rejected");
    exit();
}

$query = "SELECT r.*, u.name, u.email, u.phone, b.booking_id, b.total_fare, b.seat_numbers,
          rt.from_city, rt.to_city, rt.bus_name
          FROM refund_requests r 
          JOIN users u ON r.user_id = u.user_id 
          JOIN bookings b ON r.booking_id = b.booking_id
          JOIN routes rt ON b.route_id = rt.id
          ORDER BY 
            CASE r.status 
                WHEN 'pending' THEN 1 
                ELSE 2 
            END, 
            r.request_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Management - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .sidebar { position: fixed; left: 0; top: 0; width: 260px; height: 100%; background: #2c3e50; color: white; padding-top: 20px; }
        .sidebar h3 { text-align: center; margin-bottom: 30px; color: #2ecc71; }
        .sidebar a { display: flex; align-items: center; gap: 12px; padding: 12px 25px; color: white; text-decoration: none; }
        .sidebar a:hover { background: #27ae60; }
        .main-content { margin-left: 260px; padding: 20px; }
        .header { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        table { width: 100%; background: white; border-radius: 10px; overflow: hidden; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .btn-approve { background: #27ae60; color: white; border: none; padding: 5px 15px; border-radius: 3px; cursor: pointer; }
        .btn-reject { background: #e74c3c; color: white; border: none; padding: 5px 15px; border-radius: 3px; cursor: pointer; }
        .status-pending { background: #f39c12; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        .status-approved { background: #27ae60; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        .status-rejected { background: #e74c3c; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3><i class="fas fa-bus"></i> Admin Panel</h3>
        <a href="dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a>
        <a href="refunds.php" style="background:#27ae60;"><i class="fas fa-money-bill-wave"></i> Refunds</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-money-bill-wave"></i> Refund Requests</h2>
        </div>
        
        <?php if(isset($_GET['msg'])): ?>
            <div style="background:#d4edda; padding:10px; border-radius:5px; margin-bottom:20px;">
                Refund request <?php echo $_GET['msg']; ?> successfully!
            </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th>Amount</th>
                    <th>Reason</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['name']; ?><br><small><?php echo $row['phone']; ?></small></td>
                    <td><?php echo $row['from_city']; ?> → <?php echo $row['to_city']; ?></td>
                    <td>BDT <?php echo number_format($row['amount']); ?></td>
                    <td><?php echo substr($row['reason'], 0, 50); ?>...</td>
                    <td><?php echo date('d M, Y h:i A', strtotime($row['request_date'])); ?></td>
                    <td>
                        <span class="status-<?php echo $row['status']; ?>"><?php echo strtoupper($row['status']); ?></span>
                    </td>
                    <td>
                        <?php if($row['status'] == 'pending'): ?>
                            <a href="refunds.php?approve_id=<?php echo $row['id']; ?>" class="btn-approve" onclick="return confirm('Approve this refund?')">Approve</a>
                            <a href="refunds.php?reject_id=<?php echo $row['id']; ?>" class="btn-reject" onclick="return confirm('Reject this refund?')">Reject</a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>