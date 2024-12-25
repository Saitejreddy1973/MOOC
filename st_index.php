<?php
session_start();
include 'config.php'; // Include your database configuration file

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("Access denied: User not logged in.");
}

// Get the registration number from the session
$registration_number = $_SESSION['username'];

// Database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query to fetch registered courses based on registration_number
$sql = "SELECT * FROM registered_courses WHERE registration_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $registration_number);
$stmt->execute();
$result = $stmt->get_result();

// Create an array to hold the course data
$courses = [];

// Fetch the results into the array
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Close the statement
$stmt->close();

// Prepare the SQL query to fetch approved course credits for the student
$sql = "SELECT SUM(course_credits) as credits_earned FROM approved WHERE registration_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $registration_number);
$stmt->execute();
$result = $stmt->get_result();

$credits_earned = 0;
if ($row = $result->fetch_assoc()) {
    $credits_earned = $row['credits_earned'];
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Calculate credits to be earned
$max_credits = 16; // Maximum credits
$credits_to_be_earned = $max_credits - $credits_earned;

// Return the courses and credits as a JSON response
header('Content-Type: application/json');
echo json_encode([
    'courses' => $courses,
    'credits_earned' => $credits_earned,
    'credits_to_be_earned' => $credits_to_be_earned
]);
?>