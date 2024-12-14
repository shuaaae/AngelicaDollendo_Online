<?php
session_start(); // Start the session

// Enable error reporting for debugging (remove or set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require 'dbconnect.php'; // Ensure this includes the correct database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize the email and password from the form
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Password will be hashed, so no need to sanitize

    try {
        // Prepare the query to check credentials for customers only
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND account_type = 'customer'");
        $stmt->execute([$email]);

        // Check if any rows are returned
        if ($stmt->rowCount() > 0) {
            // If an account is found, verify the password
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                // Regenerate session ID for security
                session_regenerate_id(true);

                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];

                // Redirect to the customer dashboard
                header("Location: customer_dashboard.php");
                exit;
            } else {
                // Incorrect password
                $_SESSION['login_error'] = "Invalid email or password.";
            }
        } else {
            // No account found with this email or not a customer
            $_SESSION['login_error'] = "Invalid email or password.";
        }

        // Redirect back to the login page with the error message
        header("Location: customerlogin.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Database error: " . $e->getMessage();
        header("Location: customerlogin.php");
        exit;
    }
} else {
    // If accessed directly without form submission, redirect to login
    header("Location: customerlogin.php");
    exit;
}
?>