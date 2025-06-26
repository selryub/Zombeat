<?php include 'regmem_frame.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>FCSIT Kiosk - Track Order</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="track_order.css" />
</head>
<body>

  <!-- ✅ Notification Box (top-right corner) -->
  <div id="notification" class="notification">
    ✅ Your order is being prepared. Check your email for the receipt. If delivery is included, it will be on the way shortly.
  </div>

  <!-- Order Status Section -->
  <main class="main">
    <div class="container">

      <div class="order-status-card">
        <button class="close-btn">✕</button>

        <h1 class="order-title">ORDER STATUS</h1>
        <p class="order-id">Order ID: <!-- Optional: Insert dynamic ID here --></p>

        <div class="order-image">
          <img src="img/no-image.png" alt="Order placeholder" class="image-placeholder" />
        </div>

        <!-- Progress Tracker -->
        <div class="progress-container">
          <div class="progress-step completed">
            <div class="step-icon">
              <img src="img/clock.png" alt="Order Received" />
            </div>
            <div class="step-label">ORDER RECEIVED</div>
            <div class="step-line completed"></div>
          </div>

          <div class="progress-step completed">
            <div class="step-icon">
              <img src="img/thumbs-up.png" alt="Order Confirmed" />
            </div>
            <div class="step-label">ORDER CONFIRMED</div>
            <div class="step-line completed"></div>
          </div>

          <div class="progress-step active">
            <div class="step-icon">
              <img src="img/utensils.png" alt="Order Processed" />
            </div>
            <div class="step-label">ORDER PROCESSED</div>
            <div class="step-line"></div>
          </div>

          <div class="progress-step">
            <div class="step-icon">
              <img src="img/scooter.png" alt="On the Way" />
            </div>
            <div class="step-label">ON THE WAY</div>
            <div class="step-line"></div>
          </div>

          <div class="progress-step">
            <div class="step-icon">
              <img src="img/delivered.png" alt="Delivered" />
            </div>
            <div class="step-label">DELIVERED</div>
          </div>
        </div>

        <!-- Estimated Time Section -->
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

  <!-- ✅ JavaScript for interaction -->
  <script>
    // Redirect to home when close button is clicked
    document.querySelector('.close-btn').addEventListener('click', () => {
      window.location.href = 'user_dashboard.php'; // 🔁 Change if your homepage is named differently
    });

    // Auto-hide the notification after 5 seconds
    window.onload = () => {
      const note = document.getElementById('notification');
      if (note) {
        setTimeout(() => {
          note.style.opacity = '0';
          setTimeout(() => note.remove(), 1000);
        }, 5000);
      }
    };
  </script>

</body>
</html>
