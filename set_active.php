<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);

    $sql = "UPDATE courses SET is_active = 1 WHERE course_code = '$course_code'";

    if (!mysqli_query($conn, $sql)) {
        error_log("Error: " . mysqli_error($conn));  // Log the error for debugging
    } else {
        echo "Course marked as active!";
    }
}
?>
