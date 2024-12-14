<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "online_ordering_systemdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error); // Log error privately.
    die("Database connection failed. Please try again later."); // Generic error message for users.
}
?>
<?php
