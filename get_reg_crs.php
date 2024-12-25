<?php
header('Content-Type: application/json');

// Database connection (replace with your actual credentials)
include 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get registered courses for the logged-in student
// Assume student_id is provided somehow, e.g., through session
$student_id = $_SESSION['student_id'];
$sql = "SELECT c.name, c.code, c.duration FROM courses c
        JOIN registrations r ON c.id = r.course_id
        WHERE r.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);

$stmt->close();
$conn->close();
?>
