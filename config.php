<?php
$host = 'localhost';
$db = 'mooc_website';
$user = 'root';
$pass = '';

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the charset to utf8 (optional but recommended)
mysqli_set_charset($conn, "utf8");

?>
