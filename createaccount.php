<?php
session_start();
require 'dbconnect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Include PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure form data is set and not empty
    if (isset($_POST['fName'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {

        // Sanitize and get form data
        $fullname = trim($_POST['fName']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // If role is not set, default to customer (needed for form submission even if role is disabled)
        $role = isset($_POST['role']) ? $_POST['role'] : 'customer';

        // Validate password match
        if ($password !== $confirm_password) {
            $message = "Passwords do not match. Please try again.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Check if email or username exists
            $sql_check = "SELECT * FROM users WHERE email = ? OR uName = ?";
            $stmt = $conn->prepare($sql_check);
            $stmt->bind_param("ss", $email, $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $message = "Email or Username already exists. Please use different credentials.";
            } else {
                // Generate a unique verification token
                $token = bin2hex(random_bytes(50));

                // Insert user data into the database
                $sql_insert = "INSERT INTO users (fName, uName, email, password, role, verification_token, is_verified) 
                               VALUES (?, ?, ?, ?, ?, ?, 0)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bind_param("ssssss", $fullname, $username, $email, $hashed_password, $role, $token);

                if ($stmt->execute()) {
                    // Send verification email using PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; // Use your SMTP server (Gmail example)
                        $mail->SMTPAuth = true;
                        $mail->Username = 'lms.sorsu@gmail.com'; // Your email
                        $mail->Password = 'ouqo pbob gquk opta'; // Your email password or app-specific password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587; // Gmail SMTP Port

                        // Recipients
                        $mail->setFrom('lms.sorsu@gmail.com', 'Verify Your Account');
                        $mail->addAddress($email, $fullname); // Add recipient's email

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Account Verification';
                        $mail->Body = "
                            <h1>Verify Your Account</h1>
                            <p>Click the link below to verify your account:</p>
                            <a href='http://127.0.0.1/angelicadollendo_online/verify.php?token=$token'>Verify Email</a>";

                        // Send email
                        if ($mail->send()) {
                            $message = "Account created successfully! A verification email has been sent to your email address.";
                            $message_type = "success";  // For success message
                            echo "<script>window.location.href = 'index.php';</script>"; // Redirect after success
                        } else {
                            $message = "There was an error sending the email: " . $mail->ErrorInfo;
                            $message_type = "error"; // For error message
                        }
                    } catch (Exception $e) {
                        $message = "There was an error sending the email: " . $mail->ErrorInfo;
                        $message_type = "error"; // For error message
                    }
                } else {
                    $message = "Error: " . $stmt->error;
                    error_log("Database error: " . $stmt->error);
                    $message_type = "error"; // For error message
                }
                $stmt->close();
            }
        }
    } else {
        $message = "Please fill in all the required fields.";
        $message_type = "error"; // For error message
    }
}

// Check if there is already an admin
$sql_admin_check = "SELECT * FROM users WHERE role = 'admin'";
$result_admin_check = $conn->query($sql_admin_check);
$admin_exists = $result_admin_check->num_rows > 0;

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        html, body {
            display: flex;
            height: 100vh;
            width: 100%;
            position: relative; /* To position the background overlay */
        }

        /* Pseudo-element for transparent background overlay */
        body::after {
        content: "";
        position: fixed; /* Make the background fixed */
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: url('/angelicadollendo_online/imgs/dessertBG.png') no-repeat center center;
        background-size: cover;
        opacity: 0.6; /* Set the transparency of the background image */
        z-index: -1; /* Ensure the overlay is behind content */
        }

        .left-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .right-side {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 500px;
            background: #fff; /* Remove transparency */
            border-radius: 15px 15px 15px 15px; /* Fix border-radius */
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);
            padding: 0;
            margin-top: 120px;
        }

        .container h2 {
            font-size: 30px;
            text-align: center;
            line-height: 100px;
            color: #fff;
            background: linear-gradient(-135deg, #c850c0, #4158d0);
            margin: 0;
            padding: 0;
            border-radius: 15px 15px 0px 0px;
        }

        .container form {
            padding: 10px 30px 30px 30px;
        }

        .container form .form-control,
        .container form .form-select {
            height: 50px;
            font-size: 17px;
            border: 1px solid lightgrey;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .container form .form-control:focus,
        .container form .form-select:focus {
            border-color: #4158d0;
            box-shadow: none;
        }

        .container form label {
            font-size: 17px;
            color: #999999;
        }

        .btn-purple {
            height: 50px;
            font-size: 20px;
            font-weight: 500;
            border: none;
            border-radius: 25px;
            background: linear-gradient(-135deg, #c850c0, #4158d0);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-purple:hover {
            background: linear-gradient(-135deg, #b044b0, #3c50c0);
        }

        .btn-purple:active {
            transform: scale(0.95);
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
            color: red;
        }

        .login-link {
            text-align: center;
            margin-top: 0px;
        }

        .login-link a {
            color: #4158d0;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .logo {
            width: 300px; /* Adjust the size as needed */
            margin-left: 200px;
        }

        .logo-text {
            font-size: 40px;
            font-weight: 600;
            color: #27214f;
            margin-top: 10px;
            margin-left: 200px;
        }
    </style>
</head>
<body>

<div class="left-side">
    <img src="/angelicadollendo_online/imgs/logotrans.png" alt="Logo" class="logo">
    <div class="logo-text">Ordering System</div>
</div>

<div class="right-side">
    <div class="container">
        <h2>Register an Account</h2>
        <form method="POST" action="" onsubmit="return validatePasswords();">
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="fName" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Account Type</label>
                <select class="form-control" id="role" name="role" required <?php echo $admin_exists ? 'disabled' : ''; ?>>
    <option value="customer" <?php echo !$admin_exists ? 'selected' : ''; ?>>Customer</option>
    <option value="admin" <?php echo $admin_exists ? 'disabled' : ''; ?>>Admin</option>
</select>
            </div>
            <button type="submit" class="btn btn-purple w-100">Create Account</button>
        </form>

        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <div class="login-link">
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</div>

<script>
    function validatePasswords() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return false;
        }
        return true;
    }

     // Show notification after registration is successful
     <?php if ($message_type == 'success'): ?>
            alert("Email verification is sent to your email address!");
        <?php endif; ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>