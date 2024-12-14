<?php
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect to the login page after logout
header("Location: login.php");  // Adjusted to point to login.php in the root directory
exit();
?>
