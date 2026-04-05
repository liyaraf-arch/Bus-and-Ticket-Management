<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$title = sanitizeInput($_POST['title']);
$description = sanitizeInput($_POST['description']);
$from_city = sanitizeInput($_POST['from_city']);
$to_city = sanitizeInput($_POST['to_city']);
$price = (float)$_POST['price'];

// Handle image upload
$target_dir = "uploads/trending/";
if(!file_exists("../" . $target_dir)) {
    mkdir("../" . $target_dir, 0777, true);
}

$image_name = time() . '_' . basename($_FILES["image"]["name"]);
$target_file = $target_dir . $image_name;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Validate image
$check = getimagesize($_FILES["image"]["tmp_name"]);
if($check === false) {
    $_SESSION['error'] = "File is not an image.";
    header("Location: trending.php");
    exit();
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    header("Location: trending.php");
    exit();
}

// Upload file
if(move_uploaded_file($_FILES["image"]["tmp_name"], "../" . $target_file)) {
    $query = "INSERT INTO trending_destinations (title, description, image_path, from_city, to_city, price) 
              VALUES ('$title', '$description', '$target_file', '$from_city', '$to_city', $price)";
    
    if(mysqli_query($conn, $query)) {
        header("Location: trending.php?msg=added");
    } else {
        $_SESSION['error'] = "Database error: " . mysqli_error($conn);
        header("Location: trending.php");
    }
} else {
    $_SESSION['error'] = "Sorry, there was an error uploading your file.";
    header("Location: trending.php");
}
?>