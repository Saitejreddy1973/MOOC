<?php
session_start();
header('Content-Type: application/json'); // Ensure the content type is JSON

if (!isset($_SESSION['fa_id'])) {
    echo json_encode(["error" => "FA ID not found in session. Please log in."]);
    exit;
}

include 'config.php'; // Your DB connection

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$fa_id = $_SESSION['fa_id'];

// Fetch registered courses and certificate status for students under this FA
$sql = "SELECT rc.name, rc.registration_number, rc.course_name, rc.course_code, rc.timeline, 
        IF(c.registration_number IS NOT NULL, 'Uploaded', 'Not uploaded') AS status
        FROM registered_courses rc
        JOIN students s ON rc.registration_number = s.registration_number 
        LEFT JOIN certificates c ON rc.registration_number = c.registration_number AND rc.course_code = c.course_code
        WHERE s.fa_id = ?"; // Fetch only students who are under the logged-in FA

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $fa_id);
$stmt->execute();
$result = $stmt->get_result();

$certStatus = [];
while ($row = $result->fetch_assoc()) {
    $certStatus[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($certStatus);
?>