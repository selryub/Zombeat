// Cart data variable
let cartData = [];

// Function to get cart data from URL parameters
function getCartFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const cartDataParam = urlParams.get('cart');
    if (cartDataParam) {
        try {
            return JSON.parse(decodeURIComponent(cartDataParam));
        } catch (e) {
            console.error('Error parsing cart data:', e);
            return [];
        }
    }
    return [];
}

// Function to display cart items dynamically
function displayCartItems() {
    const cartItemsContainer = document.querySelector('.cart-items');
    
    if (cartData.length === 0) {
        cartItemsContainer.innerHTML = '<p>Your cart is empty</p>';
        return;
    }
    
    let html = '';
    cartData.forEach((item, index) => {
        // Get appropriate emoji for different food types
        let emoji = '🍱'; // default
        const itemName = item.name.toLowerCase();
        if (itemName.includes('mee') || itemName.includes('noodle') || itemName.includes('laksa') || itemName.includes('tom yam')) {
            emoji = '🍜';
        } else if (itemName.includes('drink') || itemName.includes('juice') || itemName.includes('coffee') || itemName.includes('teh') || itemName.includes('coconut')) {
            emoji = '🥤';
        } else if (itemName.includes('chicken') || itemName.includes('beef') || itemName.includes('fish')) {
            emoji = '🍖';
        } else if (itemName.includes('rice') || itemName.includes('nasi')) {
            emoji = '🍚';
        }
        
        html += `
            <div class="cart-item" data-id="${item.id}">
                <div class="item-image">
                    <span class="placeholder-icon">${emoji}</span>
                </div>
                <div class="item-details">
                    <input type="text" class="item-name-input" value="${item.name}" readonly>
                    <div class="item-price">RM ${item.price.toFixed(2)}</div>
                </div>
                <div class="quantity-controls">
                    <button class="qty-btn minus" onclick="updateQuantity(this, -1)">-</button>
                    <input type="number" class="qty-input" value="${item.quantity}" min="1" onchange="calculateTotals()">
                    <button class="qty-btn plus" onclick="updateQuantity(this, 1)">+</button>
                </div>
            </div>
        `;
    });
    
    cartItemsContainer.innerHTML = html;
}

// Toggle delivery/pickup options
function toggleDeliveryOption(option) {
    const deliveryBtn = document.getElementById('deliveryBtn');
    const pickupBtn = document.getElementById('pickupBtn');
    
    if (option === 'delivery') {
        deliveryBtn.classList.add('active');
        pickupBtn.classList.remove('active');
    } else {
        pickupBtn.classList.add('active');
        deliveryBtn.classList.remove('active');
    }
}

// Update quantity function
function updateQuantity(button, change) {
    const cartItem = button.closest('.cart-item');
    const itemId = cartItem.getAttribute('data-id');
    const qtyInput = cartItem.querySelector('.qty-input');
    let currentQty = parseInt(qtyInput.value);
    let newQty = Math.max(1, currentQty + change);
    qtyInput.value = newQty;
    
    // Update the cartData array
    const item = cartData.find(item => item.id === itemId);
    if (item) {
        item.quantity = newQty;
    }
    
    // Update totals
    calculateTotals();
}

// Calculate totals
function calculateTotals() {
    let subtotal = 0;
    
    // Calculate from cartData array
    cartData.forEach(item => {
        subtotal += item.price * item.quantity;
    });
    
    // Also update from DOM in case quantities were changed directly
    const cartItems = document.querySelectorAll('.cart-item');
    cartItems.forEach(cartItem => {
        const itemId = cartItem.getAttribute('data-id');
        const quantity = parseInt(cartItem.querySelector('.qty-input').value);
        
        // Update cartData with current quantity
        const item = cartData.find(item => item.id === itemId);
        if (item) {
            item.quantity = quantity;
        }
    });
    
    // Recalculate subtotal
    subtotal = cartData.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    const deliveryFee = 0; // Free delivery
    const total = subtotal + deliveryFee;
    
    document.getElementById('subtotal').textContent = `RM ${subtotal.toFixed(2)}`;
    document.getElementById('total').textContent = `RM ${total.toFixed(2)}`;
}

// Proceed to checkout
function proceedToCheckout() {
    if (cartData.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    // Calculate final total
    const total = cartData.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    // Create order summary
    let orderSummary = 'Order Summary:\n\n';
    cartData.forEach(item => {
        orderSummary += `${item.name} x${item.quantity} - RM ${(item.price * item.quantity).toFixed(2)}\n`;
    });
    orderSummary += `\nTotal: RM ${total.toFixed(2)}`;
    
    if (confirm(orderSummary + '\n\nProceed to transaction page?')) {
        // Here you can redirect to the next step (transaction page)
        // You can pass the cart data to the next page as well
        const cartDataParam = encodeURIComponent(JSON.stringify(cartData));
        window.location.href = `transaction.html?cart=${cartDataParam}`;
        
        // Or if you don't have a transaction page yet, show success message
        // alert('Order confirmed! Proceeding to payment...');
    }
}

// Cancel order
function cancelOrder() {
    if (confirm('Are you sure you want to cancel your order?')) {
        // Redirect back to menu page
        window.location.href = 'menu_page.html';
    }
}

// Go back to menu (add this as a new function)
function goBackToMenu() {
    window.location.href = 'menu_page.html';
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Get cart data from URL
    cartData = getCartFromURL();
    
    // Display cart items
    displayCartItems();
    
    // Calculate initial totals
    calculateTotals();
    
    // Add event listeners for delivery/pickup buttons
    document.getElementById('deliveryBtn').addEventListener('click', () => toggleDeliveryOption('delivery'));
    document.getElementById('pickupBtn').addEventListener('click', () => toggleDeliveryOption('pickup'));
});

// Function to add a "Back to Menu" button (you can add this to your HTML)
function addBackToMenuButton() {
    const actionButtons = document.querySelector('.action-buttons');
    if (actionButtons && !document.getElementById('backToMenuBtn')) {
        const backBtn = document.createElement('button');
        backBtn.id = 'backToMenuBtn';
        backBtn.className = 'btn btn-secondary';
        backBtn.textContent = 'BACK TO MENU';
        backBtn.onclick = goBackToMenu;
        actionButtons.insertBefore(backBtn, actionButtons.firstChild);
    }
}