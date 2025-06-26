<?php
require_once '../admin/db_connect.php';
include 'regmem_frame.php';

$user_email = $_SESSION['email'] ?? 'user@example.com';
$user_name = $_SESSION['username'] ?? 'Customer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FCSIT Kiosk - Billing Page</title>
  <link rel="stylesheet" href="billing.css"/>
</head>
<body>

<!-- Progress Steps -->
<div class="progress-steps">
  <div class="step"><div class="step-number">1</div><span>CART</span></div>
  <div class="step"><div class="step-number">2</div><span>TRANSACTION</span></div>
  <div class="step active"><div class="step-number">3</div><span>BILLING</span></div>
</div>

<!-- Receipt Section -->
<div class="receipt-container">
  <h1 class="receipt-title">ORDER RECEIPT</h1>

  <div class="receipt-content-row">
    <!-- CART -->
    <div class="cart-section">
      <h2 class="section-title">CART</h2>
      <div class="cart-items" id="cartItems"></div>
      <div class="remarks-section">
        <label class="remarks-label">Remarks:</label>
        <textarea class="remarks-input" id="remarks" placeholder="Any special instructions..."></textarea>
      </div>
    </div>

    <!-- ORDER INFO -->
    <div class="order-info-section">
      <div class="section-header">
        <h2 class="section-title">ORDER INFO</h2>
        <button class="close-btn">×</button>
      </div>
      <div class="order-details">
        <div class="detail-row"><span class="detail-label">Customer Name:</span><span class="detail-value"><?= htmlspecialchars($user_name) ?></span></div>
        <div class="detail-row"><span class="detail-label">Order ID:</span><span class="detail-value" id="orderId"></span></div>
        <div class="detail-row"><span class="detail-label">Date & Time:</span><span class="detail-value"><?= date('Y-m-d H:i:s') ?></span></div>
        <div class="detail-row"><span class="detail-label">Payment Method:</span><span class="detail-value" id="paymentMethod">-</span></div>
        <div class="detail-row"><span class="detail-label">Order Type:</span><span class="detail-value" id="orderType">-</span></div>
      </div>

      <h3 class="subsection-title">ORDER SUMMARY</h3>
      <div class="order-summary">
        <div class="summary-row"><span class="summary-label">Subtotal:</span><span class="summary-value" id="summarySubtotal">RM 0.00</span></div>
        <div class="summary-row"><span class="summary-label">Delivery Fee:</span><span class="summary-value" id="summaryDelivery">RM 0.00</span></div>
        <div class="summary-divider"></div>
        <div class="total-row"><span class="total-label">TOTAL:</span><span class="total-value" id="summaryTotal">RM 0.00</span></div>
      </div>

      <div class="final-remarks">
        <label class="remarks-label">Remarks:</label>
        <div class="remarks-display" id="remarksDisplay"></div>
      </div>

      <div class="action-buttons">
        <button class="btn btn-secondary" onclick="window.print()">PRINT RECEIPT</button>
        <button class="btn btn-primary" id="trackOrderBtn">TRACK ORDER</button>
      </div>
    </div>
  </div>
</div>

<script>

// ✅ Generate or reuse Order ID
let orderId = localStorage.getItem('orderId');
if (!orderId) {
  orderId = "#" + Math.floor(Math.random() * 90000 + 10000); // like #12345
  localStorage.setItem('orderId', orderId);
}
document.getElementById('orderId').textContent = orderId;


document.addEventListener('DOMContentLoaded', () => {
  const data = JSON.parse(localStorage.getItem('checkoutData')) || {};
  const cart = Array.isArray(data.items) ? data.items : [];

  // Set Payment Method
  document.getElementById('paymentMethod').textContent = data.paymentMethod || 'Not specified';

  // Set Order Type
  const orderType = data.deliveryOption === 'pickup' ? 'Pickup'
                  : data.deliveryOption === 'delivery' ? 'Delivery'
                  : 'Not specified';
  document.getElementById('orderType').textContent = orderType;

  // Fill cart
  const cartContainer = document.getElementById('cartItems');
  cartContainer.innerHTML = '';

  if (cart.length > 0) {
    cart.forEach(item => {
      cartContainer.innerHTML += `
        <div class="cart-item">
          <div class="item-image"><img src="${item.image}" alt="${item.name}" width="60" height="60" style="border-radius:10px;"></div>
          <div class="item-details">
            <div class="item-name">${item.name}</div>
            <div class="item-price">RM ${parseFloat(item.price).toFixed(2)}</div>
          </div>
          <div class="item-quantity">
            <span class="quantity-label">Quantity:</span>
            <span class="quantity-value">${item.quantity}</span>
          </div>
        </div>
      `;
    });
  } else {
    cartContainer.innerHTML = `<p>Your cart is empty.</p>`;
  }

  // Set totals
  const subtotal = parseFloat(data.subtotal) || 0;
  const deliveryFee = parseFloat(data.deliveryFee) || 0;
  const total = parseFloat(data.total) || 0;

  document.getElementById('summarySubtotal').textContent = 'RM ' + subtotal.toFixed(2);
  document.getElementById('summaryDelivery').textContent = 'RM ' + deliveryFee.toFixed(2);
  document.getElementById('summaryTotal').textContent = 'RM ' + total.toFixed(2);

  // Track Order Button
  document.getElementById('trackOrderBtn').addEventListener('click', () => {
    const remarks = document.getElementById('remarks').value.trim();
    if (remarks) {
      document.getElementById('remarksDisplay').textContent = remarks;
    }

    localStorage.removeItem('checkoutData');
    window.location.href = 'track_order.php';
  });
});
</script>
</body>
</html>



