<?php
require_once 'admin/db_connect.php';
include 'regmem_frame.php';

// Fetch all products
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>FCSIT Kiosk - Transaction Page</title>
    <link rel="stylesheet" href="transaction.css"/>
</head>
<body>

<!-- Progress Steps -->
<div class="progress-wrapper">
    <div class="progress-steps">
        <div class="step"><div class="step-number">1</div><div class="step-label">CART</div></div>
        <div class="step active"><div class="step-number">2</div><div class="step-label">TRANSACTION</div></div>
        <div class="step"><div class="step-number">3</div><div class="step-label">BILLING</div></div>
    </div>
</div>

<form id="payment-form">
    <!-- Hidden Fields -->
    <input type="hidden" name="payment_method" id="payment-method">
    <input type="hidden" name="cart_data" id="cart-data">
    <input type="hidden" name="subtotal" id="subtotal">
    <input type="hidden" name="delivery_fee" id="delivery-fee">
    <input type="hidden" name="total" id="total">

    <div class="main-content">
        <div class="checkout-grid">

            <!-- Payment Section -->
            <div class="payment-section">
                <!-- Credit Card -->
                <div class="payment-method">
                    <input type="radio" id="credit-card" name="payment" value="Credit Card" checked>
                    <label for="credit-card" class="payment-card">
                        <div class="payment-header">
                            <span class="payment-title">Credit Card</span>
                            <img src="img/creditcard.png" alt="Credit Card" class="payment-icon">
                        </div>
                        <div class="card-visual">
                            <div class="card-lines">
                                <div class="card-line"></div>
                                <div class="card-line"></div>
                                <div class="card-line"></div>
                            </div>
                        </div>
                        <div class="card-form">
                            <div class="form-row">
                                <input type="text" name="card_number" placeholder="0000 0000 0000 0000" class="card-number">
                                <input type="text" name="expiry" placeholder="MM/YY" class="expiry">
                                <input type="text" name="cvv" placeholder="CVV" class="cvv">
                            </div>
                            <input type="text" name="card_name" placeholder="Card Holder Name" class="card-name">
                        </div>
                    </label>
                </div>

                <!-- PayPal -->
                <div class="payment-method">
                    <input type="radio" id="paypal" name="payment" value="PayPal">
                    <label for="paypal" class="payment-card">
                        <div class="payment-header">
                            <span class="payment-title">PayPal</span>
                            <img src="img/paypal.png" alt="PayPal" class="paypal-logo">
                        </div>
                        <div class="paypal-placeholder">
                            <div class="placeholder-lines">
                                <div class="placeholder-line long"></div>
                                <div class="placeholder-line medium"></div>
                                <div class="placeholder-line short"></div>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Cash -->
                <div class="payment-method">
                    <input type="radio" id="cash" name="payment" value="Cash">
                    <label for="cash" class="payment-card">
                        <div class="payment-header">
                            <span class="payment-title">Cash</span>
                            <div class="cash-icon">
                                <img src="img/cash.png" alt="Cash Payment" width="40" height="30">
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Buttons inside payment box -->
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">Pay now</button>
                    <a href="view_cart.php" class="btn btn-secondary">Cancel</a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h2>ORDER SUMMARY</h2>
                <div class="order-items" id="orderItems"></div>

                <!-- Promo Code -->
                <div class="promo-code">
                    <input type="text" placeholder="Promo code" class="promo-input">
                </div>

                <!-- Totals -->
                <div class="order-totals">
                    <div class="total-row">
                        <span>SUBTOTAL</span><span id="subtotalDisplay">RM 0.00</span>
                    </div>
                    <div class="total-row">
                        <span>DELIVERY FEE</span><span id="deliveryFeeDisplay">RM 0.00</span>
                    </div>
                    <div class="total-row final-total">
                        <span>TOTAL</span><span id="totalDisplay">RM 0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cart = JSON.parse(localStorage.getItem('fcsit_kiosk_cart')) || [];

    // Render cart items
    const cartContainer = document.getElementById('orderItems');
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

    // Fill order summary
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.quantity;
    });

    const selectedDelivery = localStorage.getItem('selectedDelivery') || 'pickup';
    const delivery = selectedDelivery === 'delivery' ? 0.50 : 0.00;
    const total = subtotal + delivery;

    document.getElementById('subtotalDisplay').textContent = 'RM ' + subtotal.toFixed(2);
    document.getElementById('deliveryFeeDisplay').textContent = 'RM ' + delivery.toFixed(2);
    document.getElementById('totalDisplay').textContent = 'RM ' + total.toFixed(2);
});

// Format credit card inputs
document.querySelector('.card-number')?.addEventListener('input', function () {
    let val = this.value.replace(/\D/g, '').substring(0, 16);
    val = val.replace(/(\d{4})(?=\d)/g, '$1 ');
    this.value = val;
});

document.querySelector('.expiry')?.addEventListener('input', function () {
    let val = this.value.replace(/\D/g, '').substring(0, 4);
    if (val.length >= 3) val = val.replace(/(\d{2})(\d{1,2})/, '$1/$2');
    this.value = val;
});

document.querySelector('.cvv')?.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').substring(0, 3);
});

// Payment selection logic
document.querySelectorAll('input[name="payment"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.getElementById('payment-method').value = radio.value;
    });
});

// Handle Pay now
document.querySelector('.btn.btn-primary')?.addEventListener('click', function (e) {
    e.preventDefault();

    const selectedPayment = document.querySelector('input[name="payment"]:checked')?.value || 'Credit Card';
    const selectedDelivery = localStorage.getItem('selectedDelivery') || 'pickup';
    const cart = JSON.parse(localStorage.getItem('fcsit_kiosk_cart')) || [];

    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.quantity;
    });

    const delivery = selectedDelivery === 'delivery' ? 0.50 : 0.00;
    const total = subtotal + delivery;

    const checkoutData = {
    paymentMethod: selectedPayment,
    deliveryOption: selectedDelivery,
    subtotal: subtotal,
    deliveryFee: delivery, 
    total: total,
    items: cart
    };

    localStorage.setItem('checkoutData', JSON.stringify(checkoutData));
    window.location.href = 'billing.php';
});
</script>
</body>
</html>
