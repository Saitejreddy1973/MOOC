<?php
session_start();
include 'config.php';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT registration_number, name, course_name, course_code, course_credits, original_marks FROM approved";
$result = $conn->query($sql);

$approvedData = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $approvedData[] = $row;
    }
}

$conn->close();

echo json_encode($approvedData);
?>