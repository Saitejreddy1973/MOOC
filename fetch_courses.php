<?php
session_start();
include('config.php');

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username']; // Assume username is the registration number

// Fetch all activated courses (whether registered or not)
$query = "SELECT c.course_name, c.course_code, c.department, c.timeline, c.course_duration, c.course_credits, c.course_category, c.course_link
          FROM courses c
          WHERE c.active = 1";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();

$active_courses = [];
while ($row = $result->fetch_assoc()) {
    $active_courses[] = $row;
}

$stmt->close();
$conn->close();

// Return the active courses in JSON format
if (!empty($active_courses)) {
    echo json_encode($active_courses);
} else {
    echo json_encode(['message' => 'No active courses found']);
}
?>
