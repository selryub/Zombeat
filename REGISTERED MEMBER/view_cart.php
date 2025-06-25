<?php
require_once '../admin/db_connect.php';
include 'regmem_frame.php';

// Fetch all products
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
$conn->close();
?>

<link rel="stylesheet" href="view_cart.css" />

<div class="page-wrapper">
    <div class="cart-header">
        <h2>FCSIT KIOSK MENU</h2>
    </div>

    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="step active">
            <div class="step-number">1</div>
            <span>CART</span>
        </div>
        <div class="step">
            <div class="step-number">2</div>
            <span>TRANSACTION</span>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <span>BILLING</span>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="left-panel">
            <!-- Delivery Options -->
            <div class="delivery-options">
                <button class="option-btn active" id="deliveryBtn">DELIVERY</button>
                <button class="option-btn" id="pickupBtn">PICKUP</button>
            </div>

            <!-- Location Input -->
            <div class="location-section">
                <img src="img/location.jpg" alt="Location" height="24" height="24">
                <input type="text" class="location-input" placeholder="Location" value="Location">
            </div>

            <!-- Cart Items -->
             <div id="cart-items"></div>
            <p>Total: RM <span id="cart-total">0.00</span></p>

        <div class="right-panel">
            <div class="order-summary">
                <h3 class="summary-title">ORDER SUMMARY</h3>
                <input type="text" class="promo-input" placeholder="PROMO CODE">
                <div class="price-breakdown">
                    <div class="summary-line">
                        <span class="label">SUBTOTAL</span>
                        <span class="amount" id="subtotal">RM XX.XX</span>
                    </div>
                    <div class="summary-line">
                        <span class="label">DELIVERY FEE</span>
                        <span class="amount">FREE</span>
                    </div>
                    <div class="summary-line total-line">
                        <span class="label">TOTAL</span>
                        <span class="amount" id="total">RM XX.XX</span>
                    </div>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="proceedToCheckout()">CHECKOUT</button>
                    <button class="btn btn-secondary" onclick="cancelOrder()">CANCEL</button>
                </div>
            </div>
        </div>
    </main>

<script>
function displayCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const cart = JSON.parse(localStorage.getItem('fcsit_kiosk_cart')) || [];

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p>Your cart is empty</p>';
        cartTotalElement.textContent = '0.00';
        return;
    }

    let cartHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        cartHTML += `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.name}">
                <h4>${item.name}</h4>
                <p>RM ${item.price.toFixed(2)} × ${item.quantity}</p>
                <strong>Total: RM ${itemTotal.toFixed(2)}</strong>
            </div>
        `;
    });

    cartItemsContainer.innerHTML = cartHTML;
    cartTotalElement.textContent = total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', displayCart);
</script>

</body>
</html>
