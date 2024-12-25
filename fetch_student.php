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

// Fetch student details from the database
$query = "SELECT * FROM students WHERE registration_number='$username'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);
    
    // Return student details as JSON
    header('Content-Type: application/json');
    echo json_encode($student);
} else {
    // Return an error if no student is found
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No student found']);
}

mysqli_close($conn);
?>
