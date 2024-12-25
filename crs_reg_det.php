<?php
// Include the config.php file for database connection
include 'config.php';

// Create connection using the variables from config.php
$conn = mysqli_connect($host, $user, $pass, $db);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the charset to utf8 (optional but recommended)
mysqli_set_charset($conn, "utf8");

// SQL query to fetch registered courses from the database
$sql = "SELECT name, registration_number, course_name, course_code, timeline, course_duration, course_credits, category FROM registered_courses";
$result = mysqli_query($conn, $sql);

// Initialize an array to store the fetched data
$registered_courses = [];

// Check if any rows were returned and process the result
if ($result && mysqli_num_rows($result) > 0) {
    // Fetch each row and add it to the $registered_courses array
    while ($row = mysqli_fetch_assoc($result)) {
        $registered_courses[] = $row;
    }
} else {
    // If no data is returned or there is no result, output empty array
    echo json_encode([]);
    exit();
}

// If there's an error executing the query, display the error
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

// Close the database connection
mysqli_close($conn);

// Return the data in JSON format
header('Content-Type: application/json');
echo json_encode($registered_courses);
?>
