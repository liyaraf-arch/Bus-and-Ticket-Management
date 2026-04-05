<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Handle delete
if(isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    // Get image path
    $img_query = "SELECT image_path FROM trending_destinations WHERE id = $delete_id";
    $img_result = mysqli_query($conn, $img_query);
    $img = mysqli_fetch_assoc($img_result);
    
    // Delete image file
    if($img && file_exists('../' . $img['image_path'])) {
        unlink('../' . $img['image_path']);
    }
    
    // Delete from database
    mysqli_query($conn, "DELETE FROM trending_destinations WHERE id = $delete_id");
    header("Location: trending.php?msg=deleted");
    exit();
}

// Get all trending destinations
$query = "SELECT * FROM trending_destinations ORDER BY order_position ASC, id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trending Destinations - Admin</title>
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
        
        .sidebar a:hover, .sidebar a.active {
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-add {
            background: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .destination-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .destination-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .destination-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .destination-info {
            padding: 15px;
        }
        
        .destination-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .destination-desc {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .destination-price {
            color: #27ae60;
            font-weight: bold;
            font-size: 16px;
        }
        
        .card-actions {
            padding: 10px 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 3px;
            cursor: pointer;
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
        }
        
        .modal-content {
            background: white;
            width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .image-preview {
            max-width: 200px;
            margin-top: 10px;
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
        
        .no-data {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3><i class="fas fa-bus"></i> Admin Panel</h3>
        <a href="dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a>
        <a href="trending.php" class="active"><i class="fas fa-image"></i> Trending</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-image"></i> Manage Trending Destinations</h2>
            <button class="btn-add" onclick="openModal()">
                <i class="fas fa-plus"></i> Add New Destination
            </button>
        </div>
        
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <?php 
                if($_GET['msg'] == 'added') echo 'Destination added successfully!';
                if($_GET['msg'] == 'deleted') echo 'Destination deleted successfully!';
                ?>
            </div>
        <?php endif; ?>
        
        <div class="gallery-grid">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="destination-card">
                        <img src="../<?php echo $row['image_path']; ?>" alt="<?php echo $row['title']; ?>" class="destination-image">
                        <div class="destination-info">
                            <div class="destination-title"><?php echo $row['title']; ?></div>
                            <div class="destination-desc"><?php echo substr($row['description'], 0, 100); ?>...</div>
                            <div class="destination-price">
                                <i class="fas fa-map-marker-alt"></i> <?php echo $row['from_city']; ?> → <?php echo $row['to_city']; ?>
                            </div>
                            <div class="destination-price">
                                <i class="fas fa-tag"></i> Starting from BDT <?php echo number_format($row['price']); ?>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn-delete" onclick="deleteDestination(<?php echo $row['id']; ?>)">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-image" style="font-size: 50px; color: #ccc;"></i>
                    <p>No trending destinations added yet.</p>
                    <p>Click "Add New Destination" to get started.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h3><i class="fas fa-plus-circle"></i> Add Trending Destination</h3>
            <form id="addForm" action="save-trending.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required placeholder="e.g., Cox's Bazar Sea Beach">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Describe the destination..."></textarea>
                </div>
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
                    <label>Price (BDT)</label>
                    <input type="number" name="price" required placeholder="e.g., 1500">
                </div>
                <div class="form-group">
                    <label>Upload Image</label>
                    <input type="file" name="image" accept="image/*" required onchange="previewImage(this)">
                    <img id="imagePreview" class="image-preview" style="display: none;">
                </div>
                <button type="submit" class="btn-add" style="width: 100%;">
                    <i class="fas fa-save"></i> Save Destination
                </button>
                <button type="button" onclick="closeModal()" style="width: 100%; margin-top: 10px; padding: 10px; background: #ccc; border: none; border-radius: 5px; cursor: pointer;">
                    Cancel
                </button>
            </form>
        </div>
    </div>
    
    <script>
        function openModal() {
            document.getElementById('addModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('addForm').reset();
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
        
        function deleteDestination(id) {
            if(confirm('Are you sure you want to delete this destination?')) {
                window.location.href = `trending.php?delete_id=${id}`;
            }
        }
        
        // Close modal on outside click
        window.onclick = function(event) {
            if(event.target === document.getElementById('addModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>