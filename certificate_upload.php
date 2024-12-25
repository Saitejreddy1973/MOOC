<?php
session_start();
include 'config.php';

if (isset($_POST['courseSelect']) && isset($_POST['courseCode']) && isset($_POST['credits']) && isset($_POST['marks']) && isset($_FILES['certificateUpload'])) {
    $registration_number = $_SESSION['registration_number'];
    $course_code = $_POST['courseCode'];
    $course_name = $_POST['courseSelect'];
    $credits = $_POST['credits'];
    $marks = $_POST['marks'];

    // Fetch the student's name based on the registration number
    $nameQuery = "SELECT name FROM students WHERE registration_number = ?";
    $nameStmt = $conn->prepare($nameQuery);
    $nameStmt->bind_param("s", $registration_number);
    $nameStmt->execute();
    $nameResult = $nameStmt->get_result();

    if ($nameResult->num_rows > 0) {
        $row = $nameResult->fetch_assoc();
        $student_name = $row['name'];

        // Check for existing certificate
        $checkQuery = "SELECT * FROM certificates WHERE registration_number = ? AND course_code = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ss", $registration_number, $course_code);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "Error: Certificate already uploaded for this course.";
        } else {
            // File upload handling
            $uploadDir = 'uploads/';
            $fileName = basename($_FILES['certificateUpload']['name']);
            $uploadFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['certificateUpload']['tmp_name'], $uploadFilePath)) {
                // Insert into the database
                $query = "INSERT INTO certificates (registration_number, name, course_name, course_code, course_credits, marks, certificate_file) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssssss", $registration_number, $student_name, $course_name, $course_code, $credits, $marks, $uploadFilePath);

                if ($stmt->execute()) {
                    echo "success";
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Error: File upload failed.";
            }
        }
    } else {
        echo "Error: Student name not found.";
    }
} else {
    echo "Error: Missing required form data.";
}
?>