<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php'; // Ensure this file contains your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the username and password from the form
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if either username or password is empty
    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
        exit();
    } else {
        // Prepare the SQL query to fetch the user's role based on username and password
        $query = "SELECT role FROM users WHERE username = ? AND password = ?";
        if ($stmt = $conn->prepare($query)) {
            // Bind parameters and execute
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $stmt->bind_result($role);
            $stmt->fetch();
            $stmt->close();

            // Check if a role was found
            if (!empty($role)) {
                // Set session variables based on role
                if ($role == 'student') {
                    // Fetch the registration number for the student
                    $query = "SELECT registration_number FROM students WHERE registration_number = ?";
                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $stmt->bind_result($registration_number);
                        $stmt->fetch();
                        $stmt->close();

                        if (!empty($registration_number)) {
                            $_SESSION['registration_number'] = $registration_number; // Store registration number in session
                            $_SESSION['username'] = $username; // Store session for student
                            header("Location: st_index.html");  // Redirect student
                            exit();
                        } else {
                            echo "Registration number not found for this student.";
                            exit();
                        }
                    } else {
                        echo "Error: Could not prepare the SQL statement to fetch registration number.";
                        exit();
                    }
                } elseif ($role == 'fa') {
                    // For FAs, use the username directly as fa_id
                    $_SESSION['fa_id'] = $username; // Store fa_id in session
                    $_SESSION['username'] = $username; // Store session for FA
                    header("Location: fa_index.html");  // Redirect FA
                    exit();
                } elseif ($role == 'admin') {
                    $_SESSION['username'] = $username; // Store session for admin
                    header("Location: ad_index.html");  // Redirect admin
                    exit();
                } else {
                    echo "Invalid user role.";
                    exit();
                }
            } else {
                // If no role is found in the database
                echo "Invalid username or password.";
                exit();
            }
        } else {
            // If the SQL query fails to prepare
            echo "Error: Could not prepare the SQL statement.";
            exit();
        }
    }
}
?>