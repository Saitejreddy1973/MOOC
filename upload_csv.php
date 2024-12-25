<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Check if a file is uploaded
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    // Open the CSV file
    $file = fopen($_FILES['file']['tmp_name'], 'r');

    // Skip the header row
    fgetcsv($file);

    // Database connection
    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        $response['message'] = "Connection failed: " . $conn->connect_error;
        echo json_encode($response);
        exit;
    }

    // Loop through each row in the CSV
    while (($row = fgetcsv($file)) !== FALSE) {
        // Assign variables based on CSV columns
        $registration_number = $row[1]; // registration_number
        $course_code = $row[4]; // course_code
        $original_marks = $row[5]; // original_marks

        // Prepare statement
        $stmt = $conn->prepare("UPDATE certificates SET original_marks = ? WHERE registration_number = ? AND course_code = ?");
        $stmt->bind_param("iss", $original_marks, $registration_number, $course_code);

        // Execute the query and check for errors
        if (!$stmt->execute()) {
            $response['message'] .= "Error updating record for {$registration_number}: " . $stmt->error . "\n"; // Append errors to the message
        }
    }

    // Close the file and the database connection
    fclose($file);
    $conn->close();

    // Set success message if no errors occurred
    $response['success'] = true;
    $response['message'] = "CSV data uploaded and processed successfully.";
} else {
    $response['message'] = "Error: Please upload a valid CSV file.";
}

// Return the JSON response
echo json_encode($response);
?>
