<?php include 'regmem_frame.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FCSIT Kiosk - Track Order</title>
    <link rel="stylesheet" href="track_order.css">
</head>
<body>
    <div class="receipt-container">
        <h1 class="receipt-title">ORDER STATUS</h1>
        <div class="receipt-content">
            <div class="cart-section" style="grid-column: span 2;">
                <h2 class="section-title">Thank you!</h2>
                <p>Your order is being prepared. Please check your email for the receipt.</p>
                <p>If your order includes delivery, it will be on its way shortly.</p>
                <a href="order.php" class="btn btn-primary" style="margin-top: 20px;">Back to Menu</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main">
        <div class="container">

            <!-- Order Status Card -->
            <div class="order-status-card">
                <button class="close-btn">✕</button>
                
                <h1 class="order-title">ORDER STATUS</h1>
                <p class="order-id">Order ID:</p>
                
                <!-- Order Image Placeholder -->
                <div class="order-image">
                    <div class="image-placeholder">
                        <img src="img/no-image.png" alt="No image">
                    </div>
                </div>
                
                <!-- Progress Steps -->
                <div class="progress-container">
                    <div class="progress-step completed">
                        <div class="step-icon">
                            <img src="img/clock.png" alt="Order Received">
                        </div> 
                        <div class="step-label">ORDER RECEIVED</div>
                        <div class="step-line completed"></div>
                    </div>
                    
                    <div class="progress-step completed">
                        <div class="step-icon">
                            <img src="img/thumbs-up.png" alt="Order Confirmed">
                        </div>
                        <div class="step-label">ORDER CONFIRMED</div>
                        <div class="step-line completed"></div>
                    </div>
                    
                    <div class="progress-step active">
                        <div class="step-icon">
                            <img src="img/utensils.png" alt="Order Processed">
                        </div>
                        <div class="step-label">ORDER PROCESSED</div>
                        <div class="step-line"></div>
                    </div>
                    
                    <div class="progress-step">
                        <div class="step-icon">
                            <img src="img/scooter.png" alt="On The Way">
                        </div>
                        <div class="step-label">ON THE WAY</div>
                        <div class="step-line"></div>
                    </div>
                    
                    <div class="progress-step">
                        <div class="step-icon">
                            <img src="img/delivered.png" alt="Delivered">
                        </div>
                        <div class="step-label">DELIVERED</div>
                    </div>
                </div>
                
                <!-- Estimated Time -->
                <div class="estimated-time">
                    <h3>ESTIMATED TIME:</h3>
                    <div class="time-placeholder">
                        <div class="time-bar"></div>
                        <div class="time-bar"></div>
                        <div class="time-bar"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Simple mobile menu toggle
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.nav').classList.toggle('active');
        });

        // Close button functionality
        document.querySelector('.close-btn').addEventListener('click', function() {
            document.querySelector('.order-status-card').style.display = 'none';
        });
    </script>
    
</body>
</html>

