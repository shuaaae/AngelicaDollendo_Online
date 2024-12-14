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
    $account_type = $_POST['account_type'];

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
            display: grid;
            height: 100%;
            width: 100%;
            place-items: center;
            background: url('/angelicadollendo_online/imgs/dessertBG.png') no-repeat center center;
            background-size: cover;
        }
        .container {
            width: 380px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);
        }

    .container h2 {
        font-size: 30px;
        width: 100%; /* Ensure it matches the container's width */
        font-weight: 600;
        text-align: center;
        line-height: 100px;
        color: #fff;
        user-select: none;
        border-radius: 15px 15px 0 0; /* Matches the top border-radius of container */
        background: linear-gradient(-135deg, #c850c0, #4158d0);
        
    
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
    </style>
</head>
<body>

<div class="container">
    <h2>Register an Account</h2>
    <form method="POST" action="">
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
        
        <button type="submit" class="btn btn-purple w-100">Create Account</button>
    </form>
    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

