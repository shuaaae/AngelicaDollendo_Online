<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bundle Deals Promo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('dessertBG.png') no-repeat center center fixed;
            background-size: cover;
            color: #F1E7D9;
        }

        h1, h2, h3 {
            text-align: center;
        }

        /* Header Styles */
        .promo-header {
            background: rgba(111, 66, 193, 0.9);
            padding: 30px 20px;
            text-align: center;
            color: #F1E7D9;
            position: relative;
        }

        .promo-header h1 {
            font-size: 36px;
            margin: 0;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .promo-header p {
            font-size: 16px;
            margin-top: 5px;
        }

        .promo-header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 4px;
            background: #B80113;
            border-radius: 2px;
        }

        /* Promo Section */
        .bundle-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .bundle-card {
            flex: 1 1 calc(33.33% - 20px);
            background-color: rgba(111, 66, 193, 0.9);
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: #F1E7D9;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .bundle-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }

        .bundle-card img {
            width: 100%;
            height: auto;
            border-bottom: 2px solid #F1E7D9;
        }

        .bundle-info {
            padding: 15px;
            text-align: center;
        }

        .bundle-info h3 {
            margin: 10px 0;
            font-size: 22px;
        }

        .bundle-info .price {
            font-size: 18px;
            font-weight: bold;
            color: #B80113;
            margin: 10px 0;
        }

        .bundle-info button {
            background-color: #B80113;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .bundle-info button:hover {
            background-color: #F1E7D9;
            color: #6f42c1;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .logo img {
            width: 100px;
        }
    </style>
</head>
<body>
    <!-- Logo -->
    <div class="logo">
        <img src="logotrans.png" alt="Logo">
    </div>

    <!-- Promo Header -->
    <div class="promo-header">
        <h1>Exclusive Bundle Deals</h1>
        <p>Experience great savings with our handpicked combos – perfect for any occasion!</p>
    </div>

    <!-- Bundle Section -->
    <div class="bundle-section">
        <!-- Bundle 1 -->
        <div class="bundle-card">
            <img src="images/bundle1.png" alt="Bundle 1">
            <div class="bundle-info">
                <h3>Christmas Bomb Bundle</h3>
                <p class="price">₱200.00</p>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="item_id" value="1">
                    <input type="hidden" name="item_name" value="Christmas Bomb">
                    <input type="hidden" name="item_price" value="200">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        </div>

        <!-- Bundle 2 -->
        <div class="bundle-card">
            <img src="images/bundle2.png" alt="Bundle 2">
            <div class="bundle-info">
                <h3>Sweet Celebration Bundle</h3>
                <p class="price">₱300.00</p>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="item_id" value="2">
                    <input type="hidden" name="item_name" value="Sweet Celebration">
                    <input type="hidden" name="item_price" value="300">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        </div>

        <!-- Bundle 3 -->
        <div class="bundle-card">
            <img src="images/bundle3.png" alt="Bundle 3">
            <div class="bundle-info">
                <h3>A Family To Remember Bundle</h3>
                <p class="price">₱500.00</p>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="item_id" value="3">
                    <input type="hidden" name="item_name" value="Family Pack">
                    <input type="hidden" name="item_price" value="500">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
