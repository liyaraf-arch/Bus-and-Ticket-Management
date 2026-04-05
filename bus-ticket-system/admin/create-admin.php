<?php
require_once '../includes/config.php';

// এই ফাইলটি শুধুমাত্র একবার রান করুন, তারপর ডিলিট করে দিন

$username = 'admin';
$password = 'admin123';
$email = 'admin@trotter.com';
$name = 'Administrator';

// পাসওয়ার্ড হ্যাশ করুন
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// চেক করুন টেবিল আছে কিনা
$create_table = "CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'super_admin') DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

mysqli_query($conn, $create_table);

// অ্যাডমিন ইউজার যোগ করুন
$check = "SELECT * FROM admins WHERE username = '$username'";
$result = mysqli_query($conn, $check);

if(mysqli_num_rows($result) == 0) {
    $insert = "INSERT INTO admins (username, password, email, name, role) 
               VALUES ('$username', '$hashed_password', '$email', '$name', 'super_admin')";
    
    if(mysqli_query($conn, $insert)) {
        echo "<h2 style='color: green;'>✅ Admin user created successfully!</h2>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><a href='login.php'>Click here to login</a></p>";
    } else {
        echo "<h2 style='color: red;'>❌ Error: " . mysqli_error($conn) . "</h2>";
    }
} else {
    echo "<h2 style='color: orange;'>⚠️ Admin user already exists!</h2>";
    echo "<p><a href='login.php'>Click here to login</a></p>";
}
?>