<?php
session_start();

require 'dbconnect.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];  // Added Confirm Password
    $account_type = $_POST['account_type'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match. Please try again.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql_email_check = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql_email_check);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email already exists. Please use a different email.";
        } else {
            $sql_insert = "INSERT INTO users (fullname, email, password, account_type) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("ssss", $fullname, $email, $hashed_password, $account_type);

            if ($stmt->execute()) {
                $message = "Account created successfully! Redirecting to login page...";
                echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
            } else {
                $message = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
// Check if there is already an admin
$sql_admin_check = "SELECT * FROM users WHERE account_type = 'Admin'";
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
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
    <!-- Add your logo here -->
    <img src="/angelicadollendo_online/imgs/logotrans.png" alt="Logo" class="logo">
    <div class="logo-text">Ordering System</div>
</div>

<div class="right-side">
    <div class="container">
        <h2>Register an Account</h2>
        <form method="POST" action="" onsubmit="return validatePasswords();">
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="fullname" required>
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
                <label for="account_type" class="form-label">Account Type</label>
                <select class="form-control" id="account_type" name="account_type" required <?php echo $admin_exists ? 'disabled' : ''; ?>>
                    <option value="Customer">Customer</option>
                    <option value="Admin" <?php echo $admin_exists ? 'disabled' : ''; ?>>Admin</option>
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
    // Validate if the passwords match
    function validatePasswords() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
