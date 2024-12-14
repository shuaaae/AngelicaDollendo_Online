<?php
require 'config.php'; // Database connection

// Fetch all desserts from the database
$result = $conn->query("SELECT * FROM desserts");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dessert Menu</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Dessert Menu</h1>
    <div class="dessert-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="dessert-card">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="dessert-image">
            <h2><?php echo htmlspecialchars($row['name']); ?></h2>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p>Price: <?php echo htmlspecialchars($row['price']); ?> USD</p>
            <form method="post" action="cart.php">
                <input type="hidden" name="dessert_id" value="<?php echo $row['id']; ?>">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
        <?php } ?>
    </div>
</body>
</html>

<style>
/* styles.css */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
}

.dessert-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.dessert-card {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    text-align: center;
}

.dessert-image {
    width: 100%;
    height: auto;
    border-radius: 5px;
}
</style>