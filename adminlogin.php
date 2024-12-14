<?php
session_start(); // Start session at the very top
require 'dbconnect.php'; // Include your database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif; /* Using Poppins font */
            background-image: url('dessertBG.png'); /* Background image for dessert theme */
            background-size: cover;
            background-position: center;
            height: 100vh;
            color: #4a0072; /* Dark purple text */
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .left-section {
            width: 40%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .right-section {
            width: 30%;
            background-color: rgba(255, 255, 255, 0.8); /* Light background for form */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
            margin-left: 5%;
        }
        .logo {
            width: 120px; /* Logo size */
            margin-bottom: 20px;
        }
        h1 {
            color: #CC3333; /* Title color */
            margin-bottom: 15px;
        }
        p {
            font-size: 1rem; /* Description text */
            line-height: 1.4;
        }
        h2 {
            text-align: center; /* Centered heading */
            color: #6a1b9a; /* Purple heading color */
            margin-bottom: 15px;
        }
        form {
            color: #182052; /* Form text color */
        }
        table {
            width: 100%; /* Full width for input fields */
            margin-top: 8px; /* Space above the table */
        }
        td {
            padding: 6px; /* Padding for table cells */
        }
        input[type="email"], input[type="password"] {
            width: calc(100% - 12px); /* Full width minus padding */
            padding: 6px; /* Padding inside input fields */
            margin-bottom: 10px; /* Space below inputs */
            border-radius: 5px; /* Rounded corners for inputs */
            border: 1px solid #ddd; /* Border for inputs */
            background-color: #f8f8f8; /* Light background for inputs */
            color: #333; /* Text color for inputs */
        }
        input[type="email"]:focus, input[type="password"]:focus {
           border-color: #CC3333; 
           outline: none; 
       }
       input[type="submit"] {
           background-color: #CC3333; 
           color: white; 
           padding: 8px 12px; 
           border: none; 
           cursor: pointer; 
           border-radius: 5px; 
           width: 100%; 
           margin-top: 10px; 
       }
       input[type="submit"]:hover { 
           background-color: #a82929; 
       }
       input::placeholder { 
           color: #182052; 
       }
       .forgot-password { 
           display: block; 
           text-align: center; 
           color: #182052; 
           margin-top: 10px; 
       }
       .forgot-password a { 
           text-decoration: none; 
           color: #CC3333; 
       }
       .forgot-password a:hover { 
           text-decoration: underline; 
       }
       .error-message { 
           color: red; 
           text-align: center; 
           font-weight: bold; 
           margin-top: 15px; 
       }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
             <img src="logo.png" alt="Dessert Logo" class="logo"> <!-- Logo image -->
             <h1>Dessert Ordering System</h1> <!-- Title of the application -->
             <p>Welcome to our dessert paradise! Log in as an admin to manage orders and desserts.</p> <!-- Description -->
         </div>
         <div class="right-section">
             <form method="POST" action="alogin_process.php">
                 <h2>ADMIN LOG IN</h2>
                 <table>
                     <tr>
                         <td><input type="email" id="email" name="email" placeholder="Email" required></td>
                     </tr>
                     <tr>
                         <td><input type="password" id="password" name="password" placeholder="Password" required></td>
                     </tr>
                 </table>
                 <input type="submit" value="Login">
                 <div class="forgot-password">
                     <a href="forgot_password.php">Forgot Password?</a>
                 </div>
                 <!-- Display error message if set -->
                 <?php
                 if (isset($_SESSION['login_error'])) {
                     echo "<div class='error-message'>" . htmlspecialchars($_SESSION['login_error']) . "</div>";
                     unset($_SESSION['login_error']); // Clear the error message after displaying
                 }
                 ?>
             </form>
         </div>
     </div>
</body>
</html>