<?php
include 'config.php';

$query = "SELECT registration_number, name, course_name, course_code, course_credits, marks, original_marks, certificate_file FROM certificates";
$result = $conn->query($query);

$certificates = array();
while ($row = $result->fetch_assoc()) {
    $certificates[] = $row;
}

echo json_encode($certificates);
?>