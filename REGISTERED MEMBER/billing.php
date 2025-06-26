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
    <div class="step">
        <div class="step-number">1</div>
        <span>CART</span>
    </div>
    <div class="step">
        <div class="step-number">2</div>
        <span>TRANSACTION</span>
    </div>
    <div class="step active">
        <div class="step-number">3</div>
        <span>BILLING</span>
    </div>
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
                <div class="detail-row">
                    <span class="detail-label">Customer Name:</span>
                    <span class="detail-value"><?= htmlspecialchars($user_name) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order ID:</span>
                    <span class="detail-value" id="orderId">#<?= rand(10000, 99999) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value"><?= date('Y-m-d H:i:s') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value" id="paymentMethod">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Type:</span>
                    <span class="detail-value" id="orderType">-</span>
                </div>
            </div>

            <h3 class="subsection-title">ORDER SUMMARY</h3>
            <div class="order-summary">
                <div class="summary-row">
                    <span class="summary-label">Subtotal:</span>
                    <span class="summary-value" id="summarySubtotal">RM 0.00</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Delivery Fee:</span>
                    <span class="summary-value" id="summaryDelivery">RM 0.00</span>
                </div>
                <div class="summary-divider"></div>
                <div class="total-row">
                    <span class="total-label">TOTAL:</span>
                    <span class="total-value" id="summaryTotal">RM 0.00</span>
                </div>
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


</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const data = JSON.parse(localStorage.getItem('checkoutData')) || {};
    const cart = data.items || [];

    // Set Payment Method
    const paymentElement = document.getElementById('paymentMethod');
    paymentElement.textContent = data.paymentMethod || 'Not specified';

    // Set Order Type (based on deliveryOption: "pickup" or "delivery")
    const orderTypeElement = document.getElementById('orderType');
    orderTypeElement.textContent = data.deliveryOption === 'pickup' ? 'Pickup'
                            : data.deliveryOption === 'delivery' ? 'Delivery'
                            : 'Not specified';


    const cartContainer = document.getElementById('cartItems');
    const remarksDisplay = document.getElementById('remarksDisplay');
    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryDelivery = document.getElementById('summaryDelivery');
    const summaryTotal = document.getElementById('summaryTotal');

    cartContainer.innerHTML = '';
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

    // Fill summary
    summarySubtotal.textContent = 'RM ' + parseFloat(data.subtotal || 0).toFixed(2);
    summaryDelivery.textContent = 'RM ' + parseFloat(data.deliveryFee || 0).toFixed(2);
    summaryTotal.textContent = 'RM ' + parseFloat(data.total || 0).toFixed(2);

    // Button handler
    document.getElementById('trackOrderBtn').addEventListener('click', () => {
        const remarks = document.getElementById('remarks').value.trim();
        if (remarks) remarksDisplay.textContent = remarks;

        const itemList = cart.map(item =>
            `${item.name} x${item.quantity} — RM ${parseFloat(item.price).toFixed(2)}`
        ).join("<br>");

        const message = `
            <h2>Thank you for your order, <?= htmlspecialchars($user_name) ?>!</h2>
            <p><strong>Order Summary:</strong></p>
            <p>${itemList}</p>
            <p><strong>Subtotal:</strong> RM ${parseFloat(data.subtotal).toFixed(2)}<br>
               <strong>Delivery Fee:</strong> RM ${parseFloat(data.deliveryFee).toFixed(2)}<br>
               <strong>Total:</strong> RM ${parseFloat(data.total).toFixed(2)}</p>
            <p>We’ll notify you once your order is ready.</p>
            <p><em>FCSIT Kiosk</em></p>
        `;

        fetch('send_email.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                email: '<?= $user_email ?>',
                message: message
            })
        })
        .then(res => res.text())
        .then(response => {
            if (response.trim() === 'success') {
                document.getElementById("toast").classList.add("show");
                setTimeout(() => {
                    localStorage.removeItem('checkoutData');
                    window.location.href = 'track_order.php';
                }, 3000);
            } else {
                alert('Failed to send receipt. Please try again.');
            }
        });
    });
});
</script>
</body>
</html>


