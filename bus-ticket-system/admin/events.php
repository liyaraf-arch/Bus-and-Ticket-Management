<?php
// admin/events.php - Manage Events with Image Upload
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle delete
if(isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    // Get image path to delete file
    $img_query = "SELECT image_path FROM events WHERE id = $delete_id";
    $img_result = mysqli_query($conn, $img_query);
    $img_data = mysqli_fetch_assoc($img_result);
    if($img_data['image_path'] && file_exists('../' . $img_data['image_path'])) {
        unlink('../' . $img_data['image_path']);
    }
    
    mysqli_query($conn, "DELETE FROM events WHERE id = $delete_id");
    header("Location: events.php?msg=deleted");
    exit();
}

// Get all events
$query = "SELECT * FROM events ORDER BY event_date ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .sidebar { position: fixed; left: 0; top: 0; width: 260px; height: 100%; background: #2c3e50; color: white; padding-top: 20px; }
        .sidebar h3 { text-align: center; margin-bottom: 30px; color: #2ecc71; }
        .sidebar a { display: flex; align-items: center; gap: 12px; padding: 12px 25px; color: white; text-decoration: none; transition: all 0.3s ease; }
        .sidebar a:hover { background: #27ae60; }
        .sidebar a.active { background: #27ae60; }
        .main-content { margin-left: 260px; padding: 20px; }
        .header { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .btn-add { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; }
        .btn-add:hover { background: #219a52; }
        table { width: 100%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: 600; }
        .event-image-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        .btn-delete { background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
        .btn-delete:hover { background: #c0392b; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: white; width: 550px; max-width: 90%; padding: 25px; border-radius: 10px; max-height: 80vh; overflow-y: auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #27ae60; }
        .btn-save { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        .btn-save:hover { background: #219a52; }
        .image-preview { margin-top: 10px; max-width: 150px; border-radius: 5px; display: none; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3><i class="fas fa-bus"></i> Admin Panel</h3>
        <a href="dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a>
        <a href="dashboard.php#add-bus"><i class="fas fa-plus-circle"></i> Add Bus Route</a>
        <a href="dashboard.php#manage-buses"><i class="fas fa-bus"></i> Manage Buses</a>
        <a href="dashboard.php#bookings"><i class="fas fa-ticket-alt"></i> Bookings</a>
        <a href="dashboard.php#users"><i class="fas fa-users"></i> Users</a>
        <a href="trending.php"><i class="fas fa-image"></i> Trending</a>
        <a href="events.php" class="active"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-calendar-alt"></i> Manage Events</h2>
            <button class="btn-add" onclick="openAddModal()"><i class="fas fa-plus"></i> Add New Event</button>
        </div>
        
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert-success">
                <?php echo $_GET['msg'] == 'added' ? '✓ Event added successfully!' : '✓ Event deleted successfully!'; ?>
            </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Event Date</th>
                    <th>Location</th>
                    <th>From → To</th>
                    <th>Days Left</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($event = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $event['id']; ?></td>
                    <td>
                        <?php if($event['image_path'] && file_exists('../' . $event['image_path'])): ?>
                            <img src="../<?php echo $event['image_path']; ?>" class="event-image-preview">
                        <?php else: ?>
                            <i class="fas fa-calendar-alt" style="font-size: 30px; color: #ccc;"></i>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $event['title']; ?></td>
                    <td><?php echo date('d M, Y', strtotime($event['event_date'])); ?></td>
                    <td><?php echo $event['location']; ?></td>
                    <td><?php echo $event['from_city']; ?> → <?php echo $event['to_city']; ?></td>
                    <td><?php echo $event['days_left']; ?> days</td>
                    <td>
                        <a href="events.php?delete_id=<?php echo $event['id']; ?>" class="btn-delete" onclick="return confirm('Delete this event?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Add Event Modal with Image Upload -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-bottom: 20px; color: #27ae60;"><i class="fas fa-plus-circle"></i> Add New Event</h3>
            <form action="save-event.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Event Title *</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Event description..."></textarea>
                </div>
                <div class="form-group">
                    <label>Event Image</label>
                    <input type="file" name="event_image" accept="image/*" onchange="previewImage(this)">
                    <img id="imagePreview" class="image-preview">
                    <small style="color: #999;">Upload JPG, PNG or GIF (Max 2MB)</small>
                </div>
                <div class="form-group">
                    <label>Event Date *</label>
                    <input type="date" name="event_date" required>
                </div>
                <div class="form-group">
                    <label>Event Time</label>
                    <input type="time" name="event_time">
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date">
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" placeholder="City name">
                </div>
                <div class="form-group">
                    <label>Venue</label>
                    <input type="text" name="venue" placeholder="Specific venue name">
                </div>
                <div class="form-group">
                    <label>From City (for bus booking) *</label>
                    <select name="from_city" required>
                        <option value="Dhaka">Dhaka</option>
                        <option value="Chittagong">Chittagong</option>
                        <option value="Sylhet">Sylhet</option>
                        <option value="Cox's Bazar">Cox's Bazar</option>
                        <option value="Rajshahi">Rajshahi</option>
                        <option value="Khulna">Khulna</option>
                        <option value="Barisal">Barisal</option>
                        <option value="Rangpur">Rangpur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>To City (Event Location) *</label>
                    <select name="to_city" required>
                        <option value="Dhaka">Dhaka</option>
                        <option value="Chittagong">Chittagong</option>
                        <option value="Sylhet">Sylhet</option>
                        <option value="Cox's Bazar">Cox's Bazar</option>
                        <option value="Rajshahi">Rajshahi</option>
                        <option value="Khulna">Khulna</option>
                        <option value="Barisal">Barisal</option>
                        <option value="Rangpur">Rangpur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Days Left (auto-calculated if empty)</label>
                    <input type="number" name="days_left" placeholder="Leave empty for auto calculation">
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save Event</button>
                    <button type="button" onclick="closeModal()" style="background: #666; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('imagePreview').style.display = 'none';
        }
        
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if(input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addModal');
            if(event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>