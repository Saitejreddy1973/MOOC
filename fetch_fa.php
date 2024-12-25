<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$username = $_SESSION['username'];

// Fetch faculty advisor details from the database using ID
$query = "SELECT * FROM fas WHERE id='$username'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $fa = mysqli_fetch_assoc($result);
    
    // Return FA details as JSON
    header('Content-Type: application/json');
    echo json_encode($fa);
} else {
    // Return an error if no FA is found
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No faculty advisor found']);
}

mysqli_close($conn);
?>
