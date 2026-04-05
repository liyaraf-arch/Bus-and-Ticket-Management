<?php
// admin/save-event.php - Save Event with Image Upload
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$title = $_POST['title'];
$description = $_POST['description'];
$event_date = $_POST['event_date'];
$event_time = $_POST['event_time'];
$end_date = $_POST['end_date'];
$location = $_POST['location'];
$venue = $_POST['venue'];
$from_city = $_POST['from_city'];
$to_city = $_POST['to_city'];
$days_left = $_POST['days_left'] ? (int)$_POST['days_left'] : ceil((strtotime($event_date) - time()) / 86400);

// Handle image upload
$image_path = null;
if(isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
    $target_dir = "uploads/events/";
    
    // Create directory if not exists
    if(!file_exists("../" . $target_dir)) {
        mkdir("../" . $target_dir, 0777, true);
    }
    
    $image_name = time() . '_' . basename($_FILES['event_image']['name']);
    $target_file = $target_dir . $image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Validate image
    $check = getimagesize($_FILES['event_image']['tmp_name']);
    if($check !== false) {
        // Allow certain file formats
        if(in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            if(move_uploaded_file($_FILES['event_image']['tmp_name'], "../" . $target_file)) {
                $image_path = $target_file;
            }
        }
    }
}

$query = "INSERT INTO events (title, description, event_date, event_time, end_date, location, venue, from_city, to_city, days_left, image_path, status) 
          VALUES ('$title', '$description', '$event_date', '$event_time', " . ($end_date ? "'$end_date'" : "NULL") . ", '$location', '$venue', '$from_city', '$to_city', $days_left, " . ($image_path ? "'$image_path'" : "NULL") . ", 'upcoming')";

if(mysqli_query($conn, $query)) {
    header("Location: events.php?msg=added");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>