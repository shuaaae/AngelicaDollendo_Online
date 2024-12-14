<?php
// Start session to access user data
session_start();

// Include database connection
include('dbconnect.php');

// Check if user is logged in and has a valid ID
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user ID from the session
$user_id = $_SESSION['user_id'];

// Initialize the success message variable
$success_msg = "";

// Check if the form is submitted and process the update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data and sanitize it
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    // Prepare the SQL update query using PDO
    $query = "UPDATE users SET fullname = :fullname, address = :address, phone = :phone, email = :email WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    
    // Bind parameters to the statement
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $contact);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Execute the query and check if the update was successful
    if ($stmt->execute()) {
        // If successful, set the success message
        $success_msg = "Your information has been successfully updated!";
    } else {
        // If there was an error, set an error message
        $success_msg = "Error updating information. Please try again.";
    }
}

// Fetch user data to display in the form using PDO
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #2c3e50, #4ca1af);
            color: #ffffff;
        }

        .container {
            max-width: 450px;
            margin: 50px auto;
            background: #34495e;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #b80113;
            color: #ffffff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            line-height: 30px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .close-btn:hover {
            background: #91010f;
        }

        h1 {
            text-align: center;
            color: #4ca1af;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            outline: none;
            color: #333;
        }

        .form-group input:focus {
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
        }

        button[type="submit"] {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            background-color: #4ca1af; /* Match button color */
            color: white; /* Text color */
            border-radius: 8px; /* Rounded corners */
            border:none; /* Remove border */
            cursor:pointer; /* Pointer cursor */
        }

        button[type="submit"]:hover {
           background-color:#3b8f9a; /* Darker shade on hover */
        }

        .buttons {
           display:flex; 
           justify-content:flex-end; 
           margin-top:-10px; 
           gap:.5rem; 
       }

       footer {
           text-align:center; 
           margin-top:px; 
           font-size:.75rem; 
           color:#e0e0e0; 
       }
       
       .success-message {
           color: green; 
           text-align:center; 
           margin-top:.5rem; 
       }
       
       .error-msg {
           color:red; 
           text-align:center; 
           margin-top:.5rem; 
       }
       
   </style>
</head>
<body>
   <div class="container">
       <h1>Account Information</h1>

       <!-- Display success or error message -->
       <?php if ($success_msg): ?>
           <div class="<?= strpos($success_msg, 'Error') === false ? 'success-message' : 'error-msg' ?>">
               <?= htmlspecialchars($success_msg); ?>
           </div>
       <?php endif; ?>

       <!-- Update account form -->
       <form method="POST">
           <div class="form-group">
               <label for="fullname">Full Name:</label>
               <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($user['fullname']); ?>" required>
           </div>
           <div class="form-group">
               <label for="address">Address:</label>
               <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address']); ?>" required>
           </div>
           <div class="form-group">
               <label for="contact">Contact Number:</label>
               <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($user['phone']); ?>" required>
           </div>
           <div class="form-group">
               <label for="email">Email:</label>
               <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
           </div>
           
           <!-- Submit Button -->
           <button type="submit">Save</button>
       </form>

       <!-- Sign Out and Close Button -->
       <div class="buttons">
           <!-- Sign Out Button -->
           <a href="customerlogin.php" style="padding:.5rem;font-weight:bold;background:#b80113;color:white;border-radius:.5rem;text-decoration:none;">Sign Out</a>
           
           <!-- Close Button (Redirect to Customer Dashboard) -->
           <button class="close-btn" onclick="window.location.href='customer_dashboard.php';">X</button>
       </div>
   </div>

   <footer>
       <p>&copy; <?= date("Y"); ?> Your Company</p>
   </footer>
</body>
</html>