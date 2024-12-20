<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$host = "localhost";
$dbname = "your_database";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch sales analytics for the current month and year
try {
    // Monthly sales data
    $sql_monthly_sales = "SELECT SUM(final_total) AS total_sales, COUNT(id) AS total_orders FROM orders WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')";
    $stmt = $pdo->query($sql_monthly_sales);
    $monthly_sales = $stmt->fetch(PDO::FETCH_ASSOC);

    // Yearly sales data
    $sql_yearly_sales = "SELECT SUM(final_total) AS total_sales, COUNT(id) AS total_orders FROM orders WHERE DATE_FORMAT(created_at, '%Y') = DATE_FORMAT(CURDATE(), '%Y')";
    $stmt = $pdo->query($sql_yearly_sales);
    $yearly_sales = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching analytics: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f0fa;
            font-family: Arial, sans-serif;
        }
        .dashboard {
            padding: 2rem;
        }
        .card {
            margin-bottom: 1.5rem;
        }
        .bg-purple {
            background-color: #6f42c1;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container dashboard">
        <h1 class="mb-4">Welcome, Admin</h1>

        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-purple">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Sales Analytics (<?= date('F Y') ?>)</h5>
                        <p class="card-text">Total Sales: ₱<?= number_format($monthly_sales['total_sales'], 2) ?></p>
                        <p class="card-text">Total Orders: <?= $monthly_sales['total_orders'] ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card text-white bg-purple">
                    <div class="card-body">
                        <h5 class="card-title">Yearly Sales Analytics (<?= date('Y') ?>)</h5>
                        <p class="card-text">Total Sales: ₱<?= number_format($yearly_sales['total_sales'], 2) ?></p>
                        <p class="card-text">Total Orders: <?= $yearly_sales['total_orders'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
