<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar .logo img {
            height: 50px;
        }
        .order-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            text-align: center;
        }
        .order-status {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .order-status div {
            text-align: center;
        }
        .order-status img {
            width: 50px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">
            <img src="logo.png" alt="FCSIT Kiosk">
        </div>
        <div class="nav-links">
            <a href="index.php">HOME</a>
            <a href="menu.php">MENU</a>
            <a href="about.php">ABOUT</a>
            <a href="reviews.php">REVIEWS</a>
        </div>
    </div>

    <div class="order-container">
        <h2>Order Status</h2>
        <p>Order ID: 
            <?php
                $orderID = "123456";
                echo "<strong>$orderID</strong>";
            ?>
        </p>
        <div class="order-status">
            <div><img src="order_received.png" alt="Order Received"><p>Order Received</p></div>
            <div><img src="order_confirmed.png" alt="Order Confirmed"><p>Order Confirmed</p></div>
            <div><img src="order_processed.png" alt="Order Processed"><p>Order Processed</p></div>
            <div><img src="on_the_way.png" alt="On the Way"><p>On the Way</p></div>
            <div><img src="delivered.png" alt="Delivered"><p>Delivered</p></div>
        </div>
        <p>Estimated Time: <strong>30 minutes</strong></p>
    </div>

</body>
</html>