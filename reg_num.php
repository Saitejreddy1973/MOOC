<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Assuming username (registration number) is stored in the session
$registration_number = $_SESSION['username'];

// Send the registration number in JSON format
echo json_encode(['registration_number' => $registration_number]);
?>
