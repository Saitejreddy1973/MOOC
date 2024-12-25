<?php
header('Content-Type: application/json');

// Include the database configuration
include 'config.php';

// Check if the connection is established correctly
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// SQL query to fetch student details
$sql = "SELECT name, registration_number, section, fa_name, fa_id, batch, slot, official_mail, registered_mail, mobile, bank_account, ifsc FROM students";
$result = $conn->query($sql);

// Check if there are results and send data as JSON
if ($result->num_rows > 0) {
    $students = array();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    echo json_encode($students);
} else {
    echo json_encode(['error' => 'No records found']);
}

// Close the connection
$conn->close();
?>
