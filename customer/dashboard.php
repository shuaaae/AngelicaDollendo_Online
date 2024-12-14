<?php
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch the username from the session
$username = $_SESSION['uName'];

// Database connection
$mysqli = new mysqli("localhost", "root", "", "online_ordering_systemdb"); // Update with your database details
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch categories and products
$categories = $mysqli->query("SELECT * FROM categories");
$products = $mysqli->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4158d0;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .dashboard-container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }
        .dashboard-container h2 {
            text-align: center;
        }
        .logout-btn {
            background-color: #c850c0;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 20px auto;
        }
        .logout-btn:hover {
            background-color: #4158d0;
        }
        .category {
            margin-bottom: 20px;
        }
        .category h3 {
            color: #4158d0;
        }
        .product {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            display: inline-block;
            width: 200px;
        }
        .product h4 {
            margin: 10px 0;
        }
        .cart-btn {
            background-color: #4158d0;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .cart-btn:hover {
            background-color: #c850c0;
        }
        .order-history {
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
</div>

<div class="dashboard-container">
    <h2>Products</h2>

    <!-- Display Categories -->
    <?php while ($category = $categories->fetch_assoc()): ?>
        <div class="category">
            <h3><?php echo htmlspecialchars($category['name']); ?></h3>
            <div class="products">
                <?php
                $category_id = $category['id'];
                $category_products = $mysqli->query("SELECT * FROM products WHERE category_id = $category_id");
                while ($product = $category_products->fetch_assoc()): ?>
                    <div class="product">
                        <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                        <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1">
                            <button type="submit" class="cart-btn">Add to Cart</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>

    <h2>Order History</h2>
    <div class="order-history">
        <?php
        $user_id = $_SESSION['user_id'];
        $orders = $mysqli->query("SELECT * FROM orders WHERE user_id = $user_id");
        if ($orders->num_rows > 0):
            while ($order = $orders->fetch_assoc()): ?>
                <div class="order">
                    <p>Order ID: <?php echo htmlspecialchars($order['id']); ?></p>
                    <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
                    <p>Total: $<?php echo htmlspecialchars($order['total']); ?></p>
                </div>
            <?php endwhile;
        else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>
