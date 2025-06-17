// Shopping Cart Array
let cart = [];

// Add to Cart Function
function addToCart(button) {
    const card = button.closest('.card');
    const id = card.getAttribute('data-id');
    const name = card.getAttribute('data-name');
    const price = parseFloat(card.getAttribute('data-price'));
    
    // Check if item already exists in cart
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        // If item exists, increase quantity
        existingItem.quantity += 1;
    } else {
        // If new item, add to cart
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: 1
        });
    }
    
    // Update cart display
    updateCartDisplay();
    updateCartBadge();
    
    // Show brief confirmation
    showAddToCartConfirmation(button);
}

// Remove from Cart Function
function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    updateCartDisplay();
    updateCartBadge();
}

// Update Quantity Function
function updateQuantity(id, change) {
    const item = cart.find(item => item.id === id);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(id);
        } else {
            updateCartDisplay();
            updateCartBadge();
        }
    }
}

// Toggle Cart Sidebar
function toggleCart() {
    const cartSidebar = document.getElementById('cart-sidebar');
    cartSidebar.classList.toggle('open');
}

// Update Cart Display
function updateCartDisplay() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
        cartTotal.textContent = '0.00';
        return;
    }
    
    let html = '';
    let total = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        html += `
            <div class="cart-item">
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">RM ${item.price.toFixed(2)} each</div>
                </div>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity('${item.id}', -1)">-</button>
                    <span class="quantity">${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateQuantity('${item.id}', 1)">+</button>
                    <button class="quantity-btn" onclick="removeFromCart('${item.id}')" style="background-color: #dc3545; margin-left: 10px;">×</button>
                </div>
            </div>
        `;
    });
    
    cartItemsContainer.innerHTML = html;
    cartTotal.textContent = total.toFixed(2);
}

// Update Cart Badge
function updateCartBadge() {
    const badge = document.getElementById('cart-badge');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    if (totalItems > 0) {
        badge.textContent = totalItems;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

// Show Add to Cart Confirmation
function showAddToCartConfirmation(button) {
    const originalText = button.textContent;
    button.textContent = '✓';
    button.style.backgroundColor = '#28a745';
    
    setTimeout(() => {
        button.textContent = originalText;
        button.style.backgroundColor = '#0a58ca';
    }, 500);
}

// Modified Checkout Function - Redirects to view cart page
function checkout() {
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    // Store cart data temporarily in a global variable or pass via URL
    // Since we can't use localStorage, we'll use URL parameters for small data
    // or you can store in a global variable if both pages are part of the same session
    
    // For URL approach (good for small amounts of data):
    const cartData = encodeURIComponent(JSON.stringify(cart));
    window.location.href = `view_cart.html?cart=${cartData}`;
    
    // Alternative: Store in global variable (if pages are in same session)
    // window.cartData = cart;
    // window.location.href = 'view_cart.html';
}

// Function to get cart data from URL (for the view cart page)
function getCartFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const cartData = urlParams.get('cart');
    if (cartData) {
        try {
            return JSON.parse(decodeURIComponent(cartData));
        } catch (e) {
            console.error('Error parsing cart data:', e);
            return [];
        }
    }
    return [];
}

// Filter by Category Function
function filterCategory(category) {
    const cards = document.querySelectorAll('.card');
    const categoryButtons = document.querySelectorAll('.category-btn');
    
    // Remove active class from all buttons
    categoryButtons.forEach(btn => btn.classList.remove('active'));
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Show/hide cards based on category
    cards.forEach(card => {
        const cardCategory = card.getAttribute('data-category');
        
        if (category === 'all' || cardCategory === category) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}

// Search Items Function
function searchItems(query) {
    const cards = document.querySelectorAll('.card');
    const searchQuery = query.toLowerCase().trim();
    
    cards.forEach(card => {
        const itemName = card.getAttribute('data-name').toLowerCase();
        const itemDesc = card.querySelector('.item-desc').textContent.toLowerCase();
        
        if (searchQuery === '' || 
            itemName.includes(searchQuery) || 
            itemDesc.includes(searchQuery)) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
    
    // If searching, reset category filter
    if (searchQuery !== '') {
        const categoryButtons = document.querySelectorAll('.category-btn');
        categoryButtons.forEach(btn => btn.classList.remove('active'));
        document.querySelector('.category-btn[onclick="filterCategory(\'all\')"]').classList.add('active');
    }
}

// Initialize cart badge on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
});

// Your existing sidebar toggle function (if you have one)
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}