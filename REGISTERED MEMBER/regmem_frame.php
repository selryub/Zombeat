<?php 
session_start();  
if (isset($_SESSION["username"]) && $_SESSION["role"] !== "registered member") {     
    header("Location: login.php");     
    exit(); 
} 

$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage === 'order.php'): ?>
  <div id="cart-sidebar" class="cart-sidebar">
  <div class="cart-header">
    <h3>Your Cart</h3>
    <button class="close-cart" onclick="toggleCart()">&times;</button>
  </div>
  <div id="cart-items" class="cart-content"></div>
  <div class="cart-footer">
    <p>Total: RM <span id="cart-total">0.00</span></p>
    <a href="view_cart.php" class="checkout-btn">Proceed to Checkout</a>
  </div>
</div>

<?php endif; ?>

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8" />     
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>     
    <link rel="stylesheet" href="regmemstyle.css">

    <style>
        .cart-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        
        .cart-count {
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 18px;
            text-align: center;
        }
        
        .icons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .cart-sidebar.open {
            right: 0;
        }
        
        .cart-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .cart-content {
            padding: 20px;
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .cart-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .cart-item-price {
            color: #666;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        
        .quantity-btn {
            background: #007bff;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
        }
        
        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            padding: 5px;
        }
        
        .remove-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            margin-top: 8px;
            cursor: pointer;
            border-radius: 5px;
        }

        
        .cart-total {
            text-align: center;
            padding: 20px;
            border-top: 2px solid #007bff;
            margin-top: 20px;
        }
        
        .checkout-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }
        
        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .cart-overlay.show {
            display: block;
        }
        
        .close-cart {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
        
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeOutRight {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100%); }
        }

    </style>
</head> 
<body>      

<!-- Sidebar --> 
<div id="sidebar" class="sidebar">      
    <div class="registered_member-header">         
        <img src="img/account.png" alt="Registered Member Image" class="acc-dash">         
        <a href="regmem_dashboard.php" class="hellouser"> HELLO USER !</a>     
    </div>      

    <a href="menu_page.php" class="menuall">         
        <img src="#" class="menu">         
        <span class="dash-text">MENU</span>     
    </a>          

    <a href="order.php">         
        <img src="#" class="sales">         
        <span class="dash-text">ORDERS</span>     
    </a>        

    <a href="billing.php">         
        <img src="billing" class="billing">         
        <span class="dash-text">BILLING</span>     
    </a>      

    <a href="track_order.html" class="menuall">         
        <img src="#" class="track-orders">         
        <span class="dash-text">TRACK ORDERS</span>     
    </a>      

    <a href="profile.php">         
        <img src="img/profile2.png" class="profile">         
        <span class="dash-text">PROFILE</span>     
    </a>      

    <a href="../admin/logout.php" onclick="return confirmLogout()">
        <img src="img/logout.png" class="logout">
        <span class="dash-text">LOGOUT</span>
    </a>
</div>

<!-- Header --> 
<header class="navbar"> 
    <div class="left-header">     
        <div class="menu-icon" onclick="toggleSidebar()">☰</div>     
        <img src="img/kiosk.jpg" alt="Logo" class="logo-img">     
        <div class="logo-text">FCSIT KIOSK</div> 
    </div>      

    <nav>         
        <a href="../PUBLIC/about.php">HOME</a>         
        <a href="../PUBLIC/menu_page.php">MENU</a>         
        <a href="about.php">ABOUT</a>         
        <a href="review.php">REVIEWS</a>     
    </nav>     
    
    <div class="icons">     
        <div class="cart-container" onclick="toggleCart()">
            <img src="img/cart.png" alt="cart" class="cart-img">
            <span id="cart-count" class="cart-count">0</span>
        </div>

        <!-- Profile link -->
        <a href="profile.php">
            <img src="img/account.png" alt="account" class="acc-img">
        </a>
        <span class="icon"></span>   
    </div>  
</header>   

<!-- Cart Sidebar -->
<div class="cart-overlay" id="cart-overlay" onclick="toggleCart()"></div>
<div id="cart-sidebar" class="cart-sidebar">
    <div class="cart-header">
        <h3>My Cart</h3>
        <button class="close-cart" onclick="toggleCart()">&times;</button>
    </div>
    <div class="cart-content" id="cart-items">
        <p>Your cart is empty.</p>
    </div>
    <div class="cart-footer">
  <p>Total: RM <span id="cart-total">0.00</span></p>
  <a href="view_cart.php" class="checkout-btn">Proceed to Checkout</a>
</div>

    <div class="cart-item">
        <<img src="${item.image}" alt="${item.name}">
        <div class="cart-item-details">
            <div class="cart-item-name">${item.name}</div>
            <div class="cart-item-price">RM ${item.price.toFixed(2)} x ${item.quantity}</div>
            <div class="quantity-controls">
                <button class="quantity-btn" onclick="updateQuantity(<?= $item['product_id'] ?>, <?= $item['quantity'] - 1 ?>)">-</button>
                <input type="number" class="quantity-input" value="<?= $item['quantity'] ?>" min="1" onchange="updateQuantity(<?= $item['product_id'] ?>, this.value)">
                <button class="quantity-btn" onclick="updateQuantity(<?= $item['product_id'] ?>, <?= $item['quantity'] + 1 ?>)">+</button>
                <button class="remove-btn" onclick="removeFromCart(<?= $item['product_id'] ?>)">Remove</button>
            </div>
        </div>
    </div>
    </div>
</div>

<div id="toast-container" style="position: fixed; top: 80px; right: 20px; z-index: 9999;"></div>

<script> 
function confirmLogout() {     
    return confirm("Are you sure you want to log out?"); 
} 

// Cart functionality
function showNotification(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        background-color: ${type === 'error' ? '#dc3545' : '#28a745'};
        color: white;
        padding: 12px 20px;
        margin-top: 10px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        opacity: 0;
        transform: translateX(100%);
        animation: fadeInRight 0.5s forwards;
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'fadeOutRight 0.5s forwards';
        setTimeout(() => toast.remove(), 500);
    }, 2000);
}

function toggleCart() {
    const sidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('cart-overlay');
    
    if (sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    } else {
        sidebar.classList.add('open');
        overlay.classList.add('show');
    }
}

function checkout() {
    // Redirect to checkout page
    window.location.href = 'checkout.php';
}

// Close cart when clicking outside
document.addEventListener('click', function(event) {
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartContainer = document.querySelector('.cart-container');
    
    if (!cartSidebar.contains(event.target) && !cartContainer.contains(event.target)) {
        if (cartSidebar.classList.contains('open')) {
            toggleCart();
        }
    }
});

function confirmLogout() {
    return confirm("Are you sure you want to log out?");
}
</script>

    <!--Link to JavaScript-->
    <script src="regmem.js"></script>