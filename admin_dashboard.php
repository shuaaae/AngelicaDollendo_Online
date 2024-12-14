<?php
session_start(); // Start the session

// Include the database connection
require_once 'dbconnect.php'; // Ensure this includes the correct database connection

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Get the admin ID from the session
$admin_id = $_SESSION['admin_id'];

// Fetch admin data from the database using PDO
$query = "SELECT * FROM users WHERE id = ? AND account_type = 'admin'";
$stmt = $pdo->prepare($query); // Use $pdo instead of $conn
$stmt->execute([$admin_id]); // Execute with the admin ID as the parameter
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if admin data is found
if (!$admin) {
    echo "Admin not found or not authorized.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif; /* Using Poppins font */
            margin: 0;
            background-color: #f8e9e0; /* Light background color */
            color: #4a0072; /* Dark purple text */
        }
        .container {
            display: flex;
            height: 100vh; /* Full height of the viewport */
        }
        .sidebar {
            width: 250px; /* Sidebar width */
            background-color: rgba(13, 16, 50, 0.9); /* Dark blue with transparency */
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Space between elements */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); /* Soft shadow effect */
        }
        .sidebar h2 {
            margin: 0;
            font-size: 1.5em;
        }
        .sidebar p {
            margin: 5px 0;
        }
        .logout-btn {
            background-color: #e74c3c; /* Logout button color */
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 30px;
            text-decoration: none;
            font-size: 1.1em;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #c0392b; /* Darker red on hover */
        }
        .main-content {
            flex-grow: 1; /* Take up remaining space on the right side */
            padding: 20px; /* Padding for main content */
        }
        .main-content h1 {
            color: #6a1b9a; /* Purple heading color */
            font-size: 2em; /* Larger heading size */
        }
        .navbar {
            background-color: #ecf0f1; /* Light gray background for navigation */
            padding: 15px;
            border-radius: 5px;
        }
        .navbar ul {
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove padding */
            margin: 0; /* Remove margin */
        }
        .navbar ul li {
            margin: 10px 0; /* Space between items */
        }
        .navbar ul li a {
            color: #4a0072; /* Dark purple text for links */
            text-decoration: none; /* Remove underline from links */
            font-size: 1.1em; /* Font size for links */
            padding: 10px;
            display: block; /* Make links block elements */
            border-radius: 5px; /* Rounded corners for links */
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }
        .navbar ul li a:hover {
            background-color: #bdc3c7; /* Light gray on hover */
        }
    </style>
</head>
<body>

<!-- Sidebar Section -->
<div class="sidebar">
    <!-- User Details Section -->
    <div class="user-info">
        <h2>Welcome, <?php echo htmlspecialchars($admin['fullname']); ?></h2>
        <p><?php echo htmlspecialchars($admin['email']); ?></p>
        <p><?php echo htmlspecialchars($admin['phone']); ?></p>
    </div>

    <!-- Logout Button -->
    <a href="index.php" class="logout-btn">Logout</a>
</div>

<!-- Main Content Section -->
<div class="main-content">
    <h1>Welcome to the Admin Dashboard</h1>

    <!-- Right Side Navigation Bar -->
    <div class="navbar">
        <ul>
            <li><a href="manageaccounts.php">Manage Accounts</a></li>
            <li><a href="inventory.php">Inventory</a></li>
            <li><a href="ordermanagement.php">Order Management</a></li>
            <li><a href="salesreports.php">Sales Reports</a></li>
			<li><a href="seniorpwdid.php">Senior/PWD ID Application</a></li>
			<li><a href="feedback_rates.php">Feedback</a></li>
			<li><a href="analytics.php">Analytics Dashboard</a></li>
        </ul>
    </div>
</div>

</body>
</html>