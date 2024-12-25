<?php
session_start(); // Ensure session is started
include 'config.php'; // Database connection file

$registration_number = $_SESSION['registration_number']; // Assuming user is logged in
if (!$registration_number) {
    echo json_encode([]);
    exit;
}

$query = "SELECT course_name, course_code, course_credits FROM registered_courses WHERE registration_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $registration_number);
$stmt->execute();
$result = $stmt->get_result();

$courses = array();
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);
?>
