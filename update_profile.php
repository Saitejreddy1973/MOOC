<?php
session_start();
include('config.php');

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username']; // Assume username is the registration number

// Validate and sanitize POST data
$slot = isset($_POST['slot']) ? trim($_POST['slot']) : '';
$official_mail = isset($_POST['official_mail']) ? trim($_POST['official_mail']) : '';
$registered_mail = isset($_POST['registered_mail']) ? trim($_POST['registered_mail']) : '';
$mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
$bank_account = isset($_POST['bank_account']) ? trim($_POST['bank_account']) : '';
$ifsc = isset($_POST['ifsc']) ? trim($_POST['ifsc']) : '';

// Prepare and execute SQL query
$query = "UPDATE students SET 
            slot = ?, 
            official_mail = ?, 
            registered_mail = ?, 
            mobile = ?, 
            bank_account = ?, 
            ifsc = ? 
          WHERE registration_number = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("sssssss", $slot, $official_mail, $registered_mail, $mobile, $bank_account, $ifsc, $username);

if ($stmt->execute()) {
    // Redirect to profile with success message
    header("Location: st_profile.html?success=1");
    exit(); // Ensure no further code is executed after redirect
} else {
    // Output SQL error for debugging purposes
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
