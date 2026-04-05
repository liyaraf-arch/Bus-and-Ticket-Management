<?php
// admin/edit-bus.php - Edit Bus Route Page
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get bus ID from URL
$bus_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($bus_id == 0) {
    $_SESSION['error'] = 'Invalid bus ID';
    header("Location: dashboard.php");
    exit();
}

// Get bus details
$query = "SELECT * FROM routes WHERE id = $bus_id";
$result = mysqli_query($conn, $query);
$bus = mysqli_fetch_assoc($result);

if(!$bus) {
    $_SESSION['error'] = 'Bus not found';
    header("Location: dashboard.php");
    exit();
}

// Update bus
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from_city = sanitizeInput($_POST['from_city']);
    $to_city = sanitizeInput($_POST['to_city']);
    $bus_name = sanitizeInput($_POST['bus_name']);
    $bus_type = sanitizeInput($_POST['bus_type']);
    $departure_time = sanitizeInput($_POST['departure_time']);
    $arrival_time = sanitizeInput($_POST['arrival_time']);
    $fare = (float)$_POST['fare'];
    $total_seats = (int)$_POST['total_seats'];
    
    $update_query = "UPDATE routes SET 
                     from_city = '$from_city',
                     to_city = '$to_city',
                     bus_name = '$bus_name',
                     bus_type = '$bus_type',
                     departure_time = '$departure_time',
                     arrival_time = '$arrival_time',
                     fare = $fare,
                     total_seats = $total_seats
                     WHERE id = $bus_id";
    
    if(mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = 'Bus updated successfully!';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Update failed: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bus - Admin Panel</title>
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
        }
        
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            color: #2ecc71;
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .sidebar a:hover {
            background: #27ae60;
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
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            max-width: 600px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #27ae60;
        }
        
        .btn-save {
            background: #27ae60;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        
        .btn-cancel {
            background: #666;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3><i class="fas fa-bus"></i> Admin Panel</h3>
        <a href="dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a>
        <a href="dashboard.php#add-bus"><i class="fas fa-plus-circle"></i> Add Bus</a>
        <a href="dashboard.php#manage-buses"><i class="fas fa-bus"></i> Manage Buses</a>
        <a href="dashboard.php#bookings"><i class="fas fa-ticket-alt"></i> Bookings</a>
        <a href="dashboard.php#users"><i class="fas fa-users"></i> Users</a>
        <a href="trending.php"><i class="fas fa-image"></i> Trending</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-edit"></i> Edit Bus Route</h2>
            <p>Update bus information</p>
        </div>
        
        <div class="form-container">
            <?php if(isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> From City</label>
                    <select name="from_city" required>
                        <option value="">Select City</option>
                        <option value="Dhaka" <?php echo $bus['from_city'] == 'Dhaka' ? 'selected' : ''; ?>>Dhaka</option>
                        <option value="Chittagong" <?php echo $bus['from_city'] == 'Chittagong' ? 'selected' : ''; ?>>Chittagong</option>
                        <option value="Sylhet" <?php echo $bus['from_city'] == 'Sylhet' ? 'selected' : ''; ?>>Sylhet</option>
                        <option value="Cox's Bazar" <?php echo $bus['from_city'] == "Cox's Bazar" ? 'selected' : ''; ?>>Cox's Bazar</option>
                        <option value="Rajshahi" <?php echo $bus['from_city'] == 'Rajshahi' ? 'selected' : ''; ?>>Rajshahi</option>
                        <option value="Khulna" <?php echo $bus['from_city'] == 'Khulna' ? 'selected' : ''; ?>>Khulna</option>
                        <option value="Barisal" <?php echo $bus['from_city'] == 'Barisal' ? 'selected' : ''; ?>>Barisal</option>
                        <option value="Rangpur" <?php echo $bus['from_city'] == 'Rangpur' ? 'selected' : ''; ?>>Rangpur</option>
                        <option value="Mymensingh" <?php echo $bus['from_city'] == 'Mymensingh' ? 'selected' : ''; ?>>Mymensingh</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> To City</label>
                    <select name="to_city" required>
                        <option value="">Select City</option>
                        <option value="Dhaka" <?php echo $bus['to_city'] == 'Dhaka' ? 'selected' : ''; ?>>Dhaka</option>
                        <option value="Chittagong" <?php echo $bus['to_city'] == 'Chittagong' ? 'selected' : ''; ?>>Chittagong</option>
                        <option value="Sylhet" <?php echo $bus['to_city'] == 'Sylhet' ? 'selected' : ''; ?>>Sylhet</option>
                        <option value="Cox's Bazar" <?php echo $bus['to_city'] == "Cox's Bazar" ? 'selected' : ''; ?>>Cox's Bazar</option>
                        <option value="Rajshahi" <?php echo $bus['to_city'] == 'Rajshahi' ? 'selected' : ''; ?>>Rajshahi</option>
                        <option value="Khulna" <?php echo $bus['to_city'] == 'Khulna' ? 'selected' : ''; ?>>Khulna</option>
                        <option value="Barisal" <?php echo $bus['to_city'] == 'Barisal' ? 'selected' : ''; ?>>Barisal</option>
                        <option value="Rangpur" <?php echo $bus['to_city'] == 'Rangpur' ? 'selected' : ''; ?>>Rangpur</option>
                        <option value="Mymensingh" <?php echo $bus['to_city'] == 'Mymensingh' ? 'selected' : ''; ?>>Mymensingh</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-bus"></i> Bus Name</label>
                    <input type="text" name="bus_name" value="<?php echo $bus['bus_name']; ?>" required placeholder="e.g., Green Line Paribahan">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Bus Type</label>
                    <select name="bus_type" required>
                        <option value="AC" <?php echo $bus['bus_type'] == 'AC' ? 'selected' : ''; ?>>AC</option>
                        <option value="Non-AC" <?php echo $bus['bus_type'] == 'Non-AC' ? 'selected' : ''; ?>>Non-AC</option>
                        <option value="AC Business" <?php echo $bus['bus_type'] == 'AC Business' ? 'selected' : ''; ?>>AC Business</option>
                        <option value="Economy" <?php echo $bus['bus_type'] == 'Economy' ? 'selected' : ''; ?>>Economy</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Departure Time</label>
                    <input type="time" name="departure_time" value="<?php echo $bus['departure_time']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Arrival Time</label>
                    <input type="time" name="arrival_time" value="<?php echo $bus['arrival_time']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-money-bill-wave"></i> Fare (BDT)</label>
                    <input type="number" name="fare" value="<?php echo $bus['fare']; ?>" required placeholder="e.g., 1200">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-chair"></i> Total Seats</label>
                    <input type="number" name="total_seats" value="<?php echo $bus['total_seats']; ?>" required>
                </div>
                
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Update Bus
                </button>
                <a href="dashboard.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </form>
        </div>
    </div>
</body>
</html>