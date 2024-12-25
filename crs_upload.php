<?php
// Database connection details
include 'config.php';

// Establish connection to the database using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, display the error and exit
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Determine the action from POST or GET request
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Handle different actions based on the request
switch ($action) {

    // Upload courses to the database
    case 'upload':
        // Decode the JSON data sent via POST
        $courses = json_decode($_POST['courses'], true);

        // Check if JSON data is valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Invalid JSON data.';
            exit;
        }

        // Prepare the SQL statement for inserting or updating courses
        $stmt = $pdo->prepare("
            INSERT INTO courses (course_name, course_code, department, timeline, course_duration, course_credits, course_category, course_link, active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)
            ON DUPLICATE KEY UPDATE 
                course_name = VALUES(course_name),
                department = VALUES(department),
                timeline = VALUES(timeline),
                course_duration = VALUES(course_duration),
                course_credits = VALUES(course_credits),
                course_category = VALUES(course_category),
                course_link = VALUES(course_link)
        ");

        // Iterate through each course and insert/update it in the database
        foreach ($courses as $course) {
            try {
                $stmt->execute([
                    $course['course_name'] ?? 'N/A',
                    $course['course_code'] ?? 'N/A',
                    $course['department'] ?? 'N/A',
                    $course['timeline'] ?? 'N/A',
                    $course['course_duration'] ?? 'N/A',
                    $course['course_credits'] ?? 0,
                    $course['course_category'] ?? 'N/A',
                    $course['course_link'] ?? ''
                ]);
            } catch (PDOException $e) {
                // If an error occurs during the insertion, display the error
                echo 'Error inserting course: ' . $e->getMessage();
                exit;
            }
        }

        // If all courses are inserted successfully
        echo 'Courses uploaded successfully!';
        break;

    // Fetch all courses from the database
    case 'get_courses':
        try {
            $stmt = $pdo->query("SELECT * FROM courses");
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the list of courses in JSON format
            echo json_encode($courses);
        } catch (PDOException $e) {
            echo 'Error fetching courses: ' . $e->getMessage();
        }
        break;

    // Activate selected courses
    case 'activate':
        $courses = $_POST['courses'];
        if (empty($courses)) {
            echo 'No courses selected.';
            exit;
        }

        $placeholders = rtrim(str_repeat('?,', count($courses)), ',');
        $stmt = $pdo->prepare("UPDATE courses SET active = 1 WHERE course_code IN ($placeholders)");
        try {
            $stmt->execute($courses);
            echo 'Courses activated successfully!';
        } catch (PDOException $e) {
            echo 'Error activating courses: ' . $e->getMessage();
        }
        break;

    // Deactivate selected courses
    case 'deactivate':
        $courses = $_POST['courses'];
        if (empty($courses)) {
            echo 'No courses selected.';
            exit;
        }

        $placeholders = rtrim(str_repeat('?,', count($courses)), ',');
        $stmt = $pdo->prepare("UPDATE courses SET active = 0 WHERE course_code IN ($placeholders)");
        try {
            $stmt->execute($courses);
            echo 'Courses deactivated successfully!';
        } catch (PDOException $e) {
            echo 'Error deactivating courses: ' . $e->getMessage();
        }
        break;

    // Default case for invalid actions
    default:
        echo 'Invalid action.';
        break;
}
?>