<?php
error_reporting(E_ALL); // Enable all error reporting
ini_set('display_errors', 1); // Display errors

include 'config.php';

$sql = "SELECT * FROM courses";
$result = mysqli_query($conn, $sql);

$courses = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($courses);

mysqli_close($conn);
?>
