<?php
require_once 'admin/db_connect.php';
include 'regmem_frame.php';

// Fetch all products
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
$conn->close();
?>

<link rel="stylesheet" href="view_cart.css" />

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

<div class="main-content">
    <div class="page-container">
        <div class="left-panel">
            <!-- Delivery Options -->
            <div class="delivery-options">
                <button class="option-btn" id="deliveryBtn" onclick="selectDeliveryOption('delivery')">DELIVERY</button>
                <button class="option-btn active" id="pickupBtn" onclick="selectDeliveryOption('pickup')">PICKUP</button>
            </div>

            <div class="location-section" id="locationSection" style="display: none;">
                <img src="img/location.jpg" alt="Location" class="location-icon-img" />
                <select id="location-select" class="location-input">
                    <option value="">Select your delivery location...</option>
            </select>
        </div>


            <div class="cart-items" id="cartItemsContainer">
                <div class="empty-cart" id="emptyCartMessage">Your cart is empty</div>
            </div>
        </div>

        <div class="right-panel">
            <div class="order-summary">
                <div class="summary-title">ORDER SUMMARY</div>
                <input type="text" class="promo-input" placeholder="PROMO CODE" />

                <div class="price-breakdown">
                    <div class="summary-line">
                        <span class="label">SUBTOTAL</span>
                        <span class="amount" id="subtotal">RM 0.00</span>
                    </div>

                    <div class="summary-line">
                        <span class="label">DELIVERY FEE</span>
                        <span class="amount" id="deliveryFee">FREE</span>
                    </div>

                    <div class="summary-line total-line">
                        <span class="label">TOTAL</span>
                        <span class="amount" id="total">RM 0.00</span>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="checkout()">CHECKOUT</button>
                    <a href="order.php" class="btn btn-secondary">CANCEL</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentDeliveryOption = 'pickup'; // Default to pickup

function formatCurrency(value) {
    return 'RM ' + value.toFixed(2);
}

function selectDeliveryOption(option) {
    currentDeliveryOption = option;

    document.getElementById('deliveryBtn').classList.remove('active');
    document.getElementById('pickupBtn').classList.remove('active');

    if (option === 'delivery') {
        document.getElementById('deliveryBtn').classList.add('active');
        document.getElementById('locationSection').style.display = 'block'; // Show dropdown
    } else {
        document.getElementById('pickupBtn').classList.add('active');
        document.getElementById('locationSection').style.display = 'none'; // Hide dropdown
    }

    updateTotals();
}


function updateTotals() {
    let subtotal = 0;
    let deliveryFee = 0;

    // Calculate subtotal from all cart items
    document.querySelectorAll('.item-subtotal').forEach(el => {
        const amount = parseFloat(el.textContent.replace('RM', '').trim());
        if (!isNaN(amount)) {
            subtotal += amount;
        }
    });

    // Set delivery fee based on selected option
    if (currentDeliveryOption === 'delivery' && subtotal > 0) {
        deliveryFee = 0.50;
        document.getElementById('deliveryFee').textContent = formatCurrency(deliveryFee);
    } else {
        deliveryFee = 0;
        document.getElementById('deliveryFee').textContent = 'FREE';
    }

    // Update display
    document.getElementById('subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('total').textContent = formatCurrency(subtotal + deliveryFee);
}

function updateQuantity(index, change) {
    const quantityInput = document.getElementById(`qty-${index}`);
    let qty = parseInt(quantityInput.value);
    qty = Math.max(1, qty + change);
    quantityInput.value = qty;

    const priceElement = document.getElementById(`price-${index}`);
    const price = parseFloat(priceElement.textContent.replace('RM', '').trim());
    document.getElementById(`subtotal-${index}`).textContent = formatCurrency(price * qty);

    updateTotals();
    saveCartToStorage();
}

function removeItem(index) {
    const cart = JSON.parse(localStorage.getItem('fcsit_kiosk_cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('fcsit_kiosk_cart', JSON.stringify(cart));
    loadCartFromStorage();
}

function loadCartFromStorage() {
    const cart = JSON.parse(localStorage.getItem('fcsit_kiosk_cart')) || [];
    const container = document.getElementById('cartItemsContainer');
    
    if (cart.length === 0) {
        container.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
        updateTotals();
        return;
    }

    container.innerHTML = '';

    cart.forEach((item, index) => {
        const itemPrice = parseFloat(item.price);
        const itemSubtotal = itemPrice * item.quantity;
        
        const cartItemHTML = `
            <div class="cart-item">
                <div class="item-image">
                    <img src="${item.image}" alt="${item.name}" class="cart-item-img">
                </div>
                <div class="item-details">
                    <input type="text" class="item-name-input" value="${item.name}" readonly />
                    <div class="item-price" id="price-${index}">${formatCurrency(itemPrice)}</div>
                </div>
                <div class="quantity-controls">
                    <button class="qty-btn minus" onclick="updateQuantity(${index}, -1)">-</button>
                    <input type="text" class="qty-input" id="qty-${index}" value="${item.quantity}" readonly />
                    <button class="qty-btn plus" onclick="updateQuantity(${index}, 1)">+</button>
                </div>
                <div class="item-price item-subtotal" id="subtotal-${index}">
                    ${formatCurrency(itemSubtotal)}
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', cartItemHTML);
    });

    updateTotals();
}

function saveCartToStorage() {
    const cart = [];
    document.querySelectorAll('.cart-item').forEach((item, index) => {
        const name = item.querySelector('.item-name-input').value;
        const priceText = item.querySelector('.item-price').textContent;
        const price = parseFloat(priceText.replace('RM', '').trim());
        const quantity = parseInt(document.getElementById(`qty-${index}`).value);
        const image = item.querySelector('img')?.src || '';
        cart.push({ name, price, quantity, image });
    });
    localStorage.setItem('fcsit_kiosk_cart', JSON.stringify(cart));
}

function checkout() {
    const cart = JSON.parse(localStorage.getItem('fcsit_kiosk_cart')) || [];
    
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    if (currentDeliveryOption === 'delivery') {
        const location = document.getElementById('location-select').value;
        if (!location) {
            alert('Please select a delivery location.');
            return; // Stop the checkout process
        }
    }

    let subtotal = 0;
    const checkoutItems = cart.map(item => {
        const itemTotal = parseFloat(item.price) * parseInt(item.quantity);
        subtotal += itemTotal;
        return {
            name: item.name,
            price: item.price,
            quantity: item.quantity,
            image: item.image
        };
    });


    const deliveryFee = currentDeliveryOption === 'delivery' ? 0.50 : 0;
    const total = subtotal + deliveryFee;

    // Store checkout data for next page
    const checkoutData = {
        items: checkoutItems,
        deliveryOption: currentDeliveryOption,
        subtotal: subtotal,
        deliveryFee: deliveryFee,
        total: subtotal + deliveryFee
    };

    localStorage.setItem('checkoutData', JSON.stringify(checkoutData));
    localStorage.setItem('selectedDelivery', currentDeliveryOption);
    
    // Redirect to transaction page
    window.location.href = 'transaction.php';
}

document.addEventListener('DOMContentLoaded', () => {
    loadCartFromStorage();

    const locationSelect = document.getElementById('location-select');

    fetch('get_locations.php')
        .then(response => response.json())
        .then(locations => {
            locationSelect.innerHTML = '<option value="">Select your delivery location...</option>'; // reset options
            locations.forEach(location => {
                const option = document.createElement('option');
                option.value = location;
                option.textContent = location;
                locationSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Failed to load locations:', error);
        });
});
</script>
</body>
</html>