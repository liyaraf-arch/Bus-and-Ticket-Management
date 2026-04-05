<?php
require_once '../includes/config.php';

if(!isLoggedIn()) {
    $_SESSION['error'] = 'Please login first';
    redirect('login.php');
}

$page_title = 'My Profile';
$user_id = $_SESSION['user_id'];

// Get user details
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle profile update
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($_POST['name']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    $city = sanitizeInput($_POST['city']);
    
    // Handle image upload
    $profile_image = $user['profile_image'];
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "../uploads/profiles/";
        
        // Create directory if not exists
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $image_name = time() . '_' . basename($_FILES['profile_image']['name']);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validate image
        $check = getimagesize($_FILES['profile_image']['tmp_name']);
        if($check !== false && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            // Delete old image if not default
            if($profile_image != 'default-avatar.png' && file_exists($target_dir . $profile_image)) {
                unlink($target_dir . $profile_image);
            }
            
            if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $profile_image = $image_name;
            }
        }
    }
    
    $update_query = "UPDATE users SET name = '$name', phone = '$phone', address = '$address', city = '$city', profile_image = '$profile_image' WHERE user_id = '$user_id'";
    
    if(mysqli_query($conn, $update_query)) {
        $_SESSION['user_name'] = $name;
        $_SESSION['success'] = 'Profile updated successfully!';
        redirect('profile.php');
    } else {
        $_SESSION['error'] = 'Update failed: ' . mysqli_error($conn);
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
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            padding: 40px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }
        
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-avatar i {
            font-size: 60px;
            color: white;
        }
        
        .camera-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: rgba(0,0,0,0.6);
            border-radius: 50%;
            padding: 5px;
            font-size: 12px;
            color: white;
            display: none;
        }
        
        .profile-avatar:hover .camera-icon {
            display: block;
        }
        
        .profile-header h2 {
            color: white;
            margin-bottom: 5px;
        }
        
        .profile-header p {
            color: rgba(255,255,255,0.9);
        }
        
        .profile-body {
            padding: 30px;
        }
        
        .info-group {
            margin-bottom: 30px;
        }
        
        .info-group h3 {
            color: var(--dark-green);
            border-bottom: 2px solid var(--primary-green);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .info-label {
            width: 130px;
            font-weight: 600;
            color: #555;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .edit-btn {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-green);
        }
        
        .hidden {
            display: none;
        }
        
        .image-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
        }
        
        .btn-cancel {
            background: #666;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar" onclick="document.getElementById('avatarInput').click()">
                    <?php 
                    $avatar_path = "../uploads/profiles/" . ($user['profile_image'] ?: 'default-avatar.png');
                    if(file_exists($avatar_path) && $user['profile_image'] && $user['profile_image'] != 'default-avatar.png'): ?>
                        <img src="<?php echo $avatar_path; ?>" alt="Profile">
                    <?php else: ?>
                        <i class="fas fa-user-circle"></i>
                    <?php endif; ?>
                    <div class="camera-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <h2><?php echo $user['name']; ?></h2>
                <p><?php echo $user['email']; ?></p>
                <p><small>Member since: <?php echo date('d M, Y', strtotime($user['created_at'])); ?></small></p>
            </div>
            
            <div class="profile-body">
                <?php displayMessage(); ?>
                
                <!-- View Mode -->
                <div id="viewMode">
                    <div class="info-group">
                        <h3><i class="fas fa-user"></i> Personal Information</h3>
                        <div class="info-row">
                            <div class="info-label">Full Name:</div>
                            <div class="info-value"><?php echo $user['name']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email Address:</div>
                            <div class="info-value"><?php echo $user['email']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phone Number:</div>
                            <div class="info-value"><?php echo $user['phone']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">User ID:</div>
                            <div class="info-value"><?php echo $user['user_id']; ?></div>
                        </div>
                    </div>
                    
                    <div class="info-group">
                        <h3><i class="fas fa-map-marker-alt"></i> Address Information</h3>
                        <div class="info-row">
                            <div class="info-label">Address:</div>
                            <div class="info-value"><?php echo $user['address'] ?: 'Not provided'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">City:</div>
                            <div class="info-value"><?php echo $user['city'] ?: 'Not provided'; ?></div>
                        </div>
                    </div>
                    
                    <button class="edit-btn" onclick="toggleEdit()">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                </div>
                
                <!-- Edit Mode -->
                <div id="editMode" class="hidden">
                    <h3><i class="fas fa-edit"></i> Edit Profile</h3>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Profile Picture</label>
                            <input type="file" name="profile_image" accept="image/*" id="profileImageInput" onchange="previewProfileImage(this)">
                            <img id="profilePreview" class="image-preview" src="<?php echo $avatar_path; ?>" style="display: <?php echo ($user['profile_image'] && file_exists($avatar_path)) ? 'block' : 'none'; ?>">
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" rows="3"><?php echo $user['address']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <select name="city">
                                <option value="">Select City</option>
                                <option value="Dhaka" <?php echo $user['city'] == 'Dhaka' ? 'selected' : ''; ?>>Dhaka</option>
                                <option value="Chittagong" <?php echo $user['city'] == 'Chittagong' ? 'selected' : ''; ?>>Chittagong</option>
                                <option value="Sylhet" <?php echo $user['city'] == 'Sylhet' ? 'selected' : ''; ?>>Sylhet</option>
                                <option value="Cox's Bazar" <?php echo $user['city'] == "Cox's Bazar" ? 'selected' : ''; ?>>Cox's Bazar</option>
                                <option value="Rajshahi" <?php echo $user['city'] == 'Rajshahi' ? 'selected' : ''; ?>>Rajshahi</option>
                                <option value="Khulna" <?php echo $user['city'] == 'Khulna' ? 'selected' : ''; ?>>Khulna</option>
                                <option value="Barisal" <?php echo $user['city'] == 'Barisal' ? 'selected' : ''; ?>>Barisal</option>
                                <option value="Rangpur" <?php echo $user['city'] == 'Rangpur' ? 'selected' : ''; ?>>Rangpur</option>
                            </select>
                        </div>
                        <button type="Submit" class="edit-btn" onclick="return confirm('Are you ready to save changes');">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <button type="button" class="btn-cancel" onclick="toggleEdit()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- pages/profile.php - Add this JavaScript at the end of file -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function toggleEdit() {
        document.getElementById('viewMode').classList.toggle('hidden');
        document.getElementById('editMode').classList.toggle('hidden');
    }
    
    function previewProfileImage(input) {
        const preview = document.getElementById('profilePreview');
        if(input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Show success message when profile is updated
    <?php if(isset($_SESSION['success'])): ?>
        Swal.fire({
            title: 'Success!',
            text: '<?php echo $_SESSION['success']; ?>',
            icon: 'success',
            confirmButtonColor: '#27ae60',
            timer: 2000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        Swal.fire({
            title: 'Error!',
            text: '<?php echo $_SESSION['error']; ?>',
            icon: 'error',
            confirmButtonColor: '#e74c3c'
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>