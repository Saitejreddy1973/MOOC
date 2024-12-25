<?php

session_start();
include 'config.php';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);

$approvedData = $data['approved'];
$rejectedData = $data['rejected'];

$approvedStmt = $conn->prepare("INSERT INTO approved (registration_number, name, course_name, course_code, course_credits, original_marks) VALUES (?, ?, ?, ?, ?, ?)");
$rejectedStmt = $conn->prepare("INSERT INTO rejected (registration_number, name, course_name, course_code, course_credits, original_marks) VALUES (?, ?, ?, ?, ?, ?)");

foreach ($approvedData as $row) {
    $approvedStmt->bind_param("ssssii", $row['registration_number'], $row['name'], $row['course_name'], $row['course_code'], $row['course_credits'], $row['original_marks']);
    $approvedStmt->execute();
}

foreach ($rejectedData as $row) {
    $rejectedStmt->bind_param("ssssii", $row['registration_number'], $row['name'], $row['course_name'], $row['course_code'], $row['course_credits'], $row['original_marks']);
    $rejectedStmt->execute();
}

$approvedStmt->close();
$rejectedStmt->close();
$conn->close();

echo json_encode(['success' => true]);
?>