<?php
session_start();
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'];

// Prepare and execute the query to fetch student data
$query = "SELECT * FROM students WHERE registration_number = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
        $studentData = [
            'name' => $student['name'],
            'registration_number' => $student['registration_number'],
            'section' => $student['section'],
            'batch' => $student['batch'],
            'slot' => $student['slot'],
            'mobile_number' => $student['mobile_number'],
            'official_mail_id' => $student['official_mail_id'],
            'registered_mail_id' => $student['registered_mail_id'],
            'bank_account_number' => $student['bank_account_number'],
            'ifsc_code' => $student['ifsc_code']
        ];
    } else {
        $studentData = ['error' => 'No student found'];
    }

    $stmt->close();
} else {
    $studentData = ['error' => 'Database query failed'];
}

$conn->close();
?>