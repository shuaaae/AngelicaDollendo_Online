<?php
session_start();
require 'dbconnect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Look up the token in the users table
    $sql = "SELECT * FROM users WHERE verification_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        // Update the user's verification status and clear the token
        $sql_update = "UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $user_id);
        $stmt_update->execute();

        echo "Your email has been verified. You can now log in.";
    } else {
        echo "Invalid or expired verification token.";
    }
} else {
    echo "No token provided.";
}

$conn->close();
?>
