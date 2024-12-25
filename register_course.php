<?php
session_start();
include 'config.php'; // Ensure this is your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_code = $_POST['course_code'];
    $registration_number = $_SESSION['username']; // Assuming username is stored in session after login

    // Fetch student details to get name
    $query = "SELECT name FROM students WHERE registration_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $registration_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $name = $student['name'];

        // Fetch course details based on course_code
        $course_query = "SELECT course_name, timeline, course_duration, course_credits, course_category FROM courses WHERE course_code = ?";
        $course_stmt = $conn->prepare($course_query);
        $course_stmt->bind_param("s", $course_code);
        $course_stmt->execute();
        $course_result = $course_stmt->get_result();

        if ($course_result->num_rows > 0) {
            $course_details = $course_result->fetch_assoc();
            $course_name = $course_details['course_name'];
            $timeline = $course_details['timeline']; // Get the timeline from the courses table
            $course_duration = $course_details['course_duration'];
            $course_credits = $course_details['course_credits'];
            $category = $course_details['course_category'];

            // Check if the course is already registered by this student
            $check_query = "SELECT * FROM registered_courses WHERE registration_number = ? AND course_code = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("ss", $registration_number, $course_code);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                echo json_encode(["success" => false, "message" => "Course already registered."]);
            } else {
                // Insert into registered_courses table
                $insert_query = "INSERT INTO registered_courses (name, registration_number, course_name, course_code, timeline, course_duration, course_credits, category) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("ssssssis", $name, $registration_number, $course_name, $course_code, $timeline, $course_duration, $course_credits, $category);

                if ($insert_stmt->execute()) {
                    echo json_encode(["success" => true, "message" => "Registered successfully!"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Registration failed."]);
                }
            }
        } else {
            echo json_encode(["success" => false, "message" => "Course not found."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Student not found."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>