<?php
session_start();
include('config.php');

// Check if FA is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'FA not logged in']);
    exit();
}

$fa_id = $_SESSION['username'];

// Fetch students associated with this FA
$query = "SELECT * FROM students WHERE fa_id='$fa_id'";
$result = mysqli_query($conn, $query);

$students = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
    
    // Return students as JSON
    echo json_encode($students);
} else {
    echo json_encode(['error' => 'No students found']);
}

mysqli_close($conn);
?>
