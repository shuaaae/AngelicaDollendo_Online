<?php
session_start();
require 'dbconnect.php'; // Include your database connection

// Function to debug session data (optional)
function debug_to_console($data) {
    $output = $data;
    if (is_array($output)) {
        $output = implode(',', $output);
    }
    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

// Debug session data
debug_to_console($_SESSION);

// Fetch the username based on the logged-in user's session (if available)
$username = ""; // Default value
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT uName FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = htmlspecialchars($user['uName'], ENT_QUOTES, 'UTF-8'); // Sanitize output
    }
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username from the form (instead of email)
    if (isset($_POST['uName'])) {
        $username = $_POST['uName'];
    } else {
        // Handle the case where username is not provided (optional, you can set an error message here)
        $username = '';
    }

    $password = $_POST['password'];

    // Query the database for the user with the provided username (not email)
    $sql = "SELECT * FROM users WHERE uName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User found, check the password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password matches, set session variables and redirect to dashboard
            $_SESSION['user_id'] = $user['id']; // Assuming you have a user 'id' field
            $_SESSION['uName'] = $user['uName']; // Store the username in session
            $_SESSION['fullname'] = $user['fullname']; // Store fullname in session
            $_SESSION['email'] = $user['email']; // Store email in sessioni

            // Redirect to customer dashboard
            header("Location:./customer/dashboard.php");
            exit();
        } else {
            // Incorrect password
            $message = "Invalid username or password.";
        }
    } else {
        // Username not found
        $message = "Invalid username or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Login Form</title>
      <style>
         @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
         * {
           margin: 0;
           padding: 0;
           box-sizing: border-box;
           font-family: 'Poppins', sans-serif;
         }
         html, body {
           display: grid;
           height: 100%;
           width: 100%;
           place-items: center;
           background: url('/angelicadollendo_online/imgs/dessertBG.png') no-repeat center center;
           background-size: cover;
         }
         ::selection {
           background: #4158d0;
           color: #fff;
         }
         .wrapper {
           width: 380px;
           background: #fff;
           border-radius: 15px;
           box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);
         }
         .wrapper .title {
           font-size: 35px;
           font-weight: 600;
           text-align: center;
           line-height: 100px;
           color: #fff;
           user-select: none;
           border-radius: 15px 15px 0 0;
           background: linear-gradient(-135deg, #c850c0, #4158d0);
         }
         .wrapper form {
           padding: 10px 30px 50px 30px;
         }
         .wrapper form .field {
           height: 50px;
           width: 100%;
           margin-top: 20px;
           position: relative;
         }
         .wrapper form .field input {
           height: 100%;
           width: 100%;
           outline: none;
           font-size: 17px;
           padding-left: 20px;
           border: 1px solid lightgrey;
           border-radius: 25px;
           transition: all 0.3s ease;
         }
         .wrapper form .field input:focus,
         form .field input:valid {
           border-color: #4158d0;
         }
         .wrapper form .field label {
           position: absolute;
           top: 50%;
           left: 20px;
           color: #999999;
           font-weight: 400;
           font-size: 17px;
           pointer-events: none;
           transform: translateY(-50%);
           transition: all 0.3s ease;
         }
         form .field input:focus ~ label,
         form .field input:valid ~ label {
           top: 0%;
           font-size: 16px;
           color: #4158d0;
           background: #fff;
           transform: translateY(-50%);
         }
         form .content {
           display: flex;
           width: 100%;
           height: 50px;
           font-size: 16px;
           align-items: center;
           justify-content: space-around;
         }
         form .content .checkbox {
           display: flex;
           align-items: center;
           justify-content: center;
         }
         form .content input {
           width: 15px;
           height: 15px;
           background: red;
         }
         form .content label {
           color: #262626;
           user-select: none;
           padding-left: 5px;
         }
         form .content .pass-link {
           color: "";
         }
         form .field input[type="submit"] {
           color: #fff;
           border: none;
           padding-left: 0;
           margin-top: -10px;
           font-size: 20px;
           font-weight: 500;
           cursor: pointer;
           background: linear-gradient(-135deg, #c850c0, #4158d0);
           transition: all 0.3s ease;
         }
         form .field input[type="submit"]:active {
           transform: scale(0.95);
         }
         form .signup-link {
           color: #262626;
           margin-top: 20px;
           text-align: center;
         }
         form .pass-link a,
         form .signup-link a {
           color: #4158d0;
           text-decoration: none;
         }
         form .pass-link a:hover,
         form .signup-link a:hover {
           text-decoration: underline;
         }

      </style>
   </head>
   <body>
      <div class="wrapper">
         <div class="title">
            Login Form
         </div>
         <form action="" method="POST">
         <div class="field">
        <input type="text" name="uName" value="<?php echo $username; ?>" required>
        <label>Username</label>
    </div>
            <div class="field">
               <input type="password" name="password" required>
               <label>Password</label>
            </div>
            <div class="content">
               <div class="checkbox">
                  <input type="checkbox" id="remember-me">
                  <label for="remember-me">Remember me</label>
               </div>
               <div class="pass-link">
                  <a href="#">Forgot password?</a>
               </div>
            </div>
            <div class="field">
               <input type="submit" value="Login">
            </div>
            <div class="signup-link">
               Don't have an account? <a href="createaccount.php">Signup now</a>
            </div>
         </form>
         <?php
            if (isset($message)) {
                echo "<p style='color: red; text-align: center;'>$message</p>";
            }
         ?>
      </div>
   </body>
</html>
