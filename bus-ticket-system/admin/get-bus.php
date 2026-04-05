<?php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$id = (int)$_GET['id'];

$query = "SELECT * FROM routes WHERE id = $id";
$result = mysqli_query($conn, $query);
$bus = mysqli_fetch_assoc($result);

echo json_encode($bus);
?>