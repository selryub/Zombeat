<?php
session_start();

require_once '../admin/db_connect.php';

// Get category and search input from URL
$category = $_GET['category'] ?? 'ALL';
$search = $_GET['search'] ?? '';

// Start building SQL query
$sql = "SELECT * FROM product WHERE 1";
$params = [];
$types = "";

// If category is not 'All', add condition
if ($category !== 'ALL') {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

// If search keyword is not empty, add condition
if (!empty($search)) {
    $sql .= " AND product_name LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

// Prepare and bind if parameters exist
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Execute and fetch
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();

// Calculate cart total and count
$cart_total = 0;
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_total += $item['price'] * $item['quantity'];
        $cart_count += $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FCSIT Kiosk - Menu</title>
  <link rel="stylesheet" href="../PUBLIC/index.css" />
  <link rel="stylesheet" href="../PUBLIC/menu_page.css" />
  <style>
    /* Cart sidebar styles */
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
      background: #dc3545;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
      margin-left: 10px;
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
    
    .cart-count {
      background: #dc3545;
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
      font-size: 12px;
      position: absolute;
      top: -5px;
      right: -5px;
    }
    
    .cart-container {
      position: relative;
      display: inline-block;
    }
    
    .close-cart {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
    }
    
    /* Notification animations */
    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    
    @keyframes slideOutRight {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }
  </style>
</head>
<body>

<div class="page-wrapper">
<!-- Header -->
<header class="navbar">
<div class="left-header">
  <a href="menu_page.php">
  <img src="../PUBLIC/img/kiosk.jpg" alt="Logo" class="logo-img">
  </a>
  <div class="logo-text">FCSIT KIOSK</div>
</div>

  <nav>
    <a href="menu_page.php">HOME</a>
    <a href="menu_page.php">MENU</a>
    <a href="about.php">ABOUT</a>
    <a href="#">REVIEWS</a>
  </nav>
  <div class="icons">
    <div class="cart-container" onclick="toggleCart()" style="cursor: pointer;">
      <img src="../PUBLIC/img/cart.png" alt="cart" class="cart-img">
      <?php if ($cart_count > 0): ?>
        <span class="cart-count" id="cart-count"><?= $cart_count ?></span>
      <?php endif; ?>
    </div>
    <a href="profile.php" style="cursor: pointer;"><img src="../PUBLIC/img/account.png" alt="account" class="acc-img"></a>
  </div>
</header>

<div class="menu-header-bar">
  <h2>MENU</h2>
  <form method="GET" action="menu_page.php">
    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
    <input type="text" name="search" class="search-menu" placeholder=" 🔍︎ Search" value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
  </form>
</div>

<div class="menu-container">
  <div class="menu-text">
    <div class="categories">
      <?php
      $categories = ['ALL', 'HEAVY FOODS', 'SNACKS', 'DRINKS'];
      foreach ($categories as $cat) {
          $activeClass = ($cat === $category) ? 'active-category' : '';
          $searchParam = !empty($search) ? '&search=' . urlencode($search) : '';
          echo "<a href='?category=" . urlencode($cat) . $searchParam . "'><span class='$activeClass'>$cat</span></a>";
      }
      ?>
    </div>
    <div class="items">
      <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
              <div class="card">
                  <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                  <p class="item-name"><?= htmlspecialchars($row['product_name']) ?></p>
                  <div class="card-text">
                      <p class="item-desc"><?= htmlspecialchars($row['description']) ?></p>
                      <div class="price-button">
                          <strong class="price">RM <?= number_format($row['price'], 2) ?></strong>
                          <button onclick="addToCart(<?= $row['product_id'] ?>, '<?= htmlspecialchars($row['product_name'], ENT_QUOTES) ?>', <?= $row['price'] ?>, '<?= htmlspecialchars($row['image_url'], ENT_QUOTES) ?>')">+</button>
                      </div>
                  </div>
              </div>
          <?php endwhile; ?>
      <?php else: ?>
          <p>No items found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

</div>

<!-- Cart Sidebar -->
<div class="cart-overlay" id="cart-overlay" onclick="toggleCart()"></div>
<div class="cart-sidebar" id="cart-sidebar">
  <div class="cart-header">
    <h3>My Cart</h3>
    <button class="close-cart" onclick="toggleCart()">&times;</button>
  </div>
  <div class="cart-content" id="cart-content">
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
      <?php foreach ($_SESSION['cart'] as $item): ?>
        <div class="cart-item" id="cart-item-<?= $item['product_id'] ?>">
          <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
          <div class="cart-item-details">
            <div class="cart-item-name"><?= htmlspecialchars($item['product_name']) ?></div>
            <div class="cart-item-price">RM <?= number_format($item['price'], 2) ?></div>
            <div class="quantity-controls">
              <button class="quantity-btn" onclick="updateQuantity(<?= $item['product_id'] ?>, <?= $item['quantity'] - 1 ?>)">-</button>
              <input type="number" class="quantity-input" value="<?= $item['quantity'] ?>" min="1" onchange="updateQuantity(<?= $item['product_id'] ?>, this.value)">
              <button class="quantity-btn" onclick="updateQuantity(<?= $item['product_id'] ?>, <?= $item['quantity'] + 1 ?>)">+</button>
              <button class="remove-btn" onclick="removeFromCart(<?= $item['product_id'] ?>)">Remove</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <div class="cart-total">
        <h3>Total: RM <span id="cart-total"><?= number_format($cart_total, 2) ?></span></h3>
        <button class="checkout-btn" onclick="checkout()">Checkout</button>
      </div>
    <?php else: ?>
      <p>Your cart is empty.</p>
    <?php endif; ?>
  </div>
</div>

<footer class="footer">
  <div class="contact">
    <p>CONTACT</p>
    <p>Exco Keusahawanan (Kiosk)
    <br>Persatuan Teknologi Maklumat (PERTEKMA)
    <br>Fakulti Sains Komputer dan Teknologi Maklumat
    <br>Univeristi Malaysia Sarawak
    </p>
  </div>

  <div class="map-container">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d249.28178855754!2d110.42879137977478!3d1.4681128239800552!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31fba3ce88e3a469%3A0xf0983c853635b29!2sFaculty%20of%20Computer%20Science%20%26%20Information%20Technology%20(FCSIT)!5e0!3m2!1sen!2smy!4v1748969425700!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>

  <div class="straightline">
    <img src="../PUBLIC/img/blackline.png">
  </div>

  <div class="hours">
    <p>OPENING HOURS</p>
    <p>MON - FRI: 8AM - 6PM</p>
    <p>SATURDAY & SUNDAY: CLOSED</p>
  </div>
</footer>

<script>
function addToCart(productId, productName, price, imageUrl) {
    const formData = new FormData();
    formData.append('action', 'add_to_cart');
    formData.append('product_id', productId);
    formData.append('product_name', productName);
    formData.append('price', price);
    formData.append('image_url', imageUrl);
    
    fetch('cart_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay();
            updateCartCount(data.cart_count);
            showNotification(data.message);
        } else {
            showNotification('Error adding item to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding item to cart', 'error');
    });
}

function removeFromCart(productId) {
    if (confirm('Are you sure you want to remove this item from cart?')) {
        const formData = new FormData();
        formData.append('action', 'remove_from_cart');
        formData.append('product_id', productId);
        
        fetch('cart_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartDisplay();
                updateCartCount(data.cart_count);
                showNotification(data.message);
            } else {
                showNotification('Error removing item from cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error removing item from cart', 'error');
        });
    }
}

function updateQuantity(productId, quantity) {
    if (quantity < 1) {
        removeFromCart(productId);
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'update_cart');
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    
    fetch('cart_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay();
            updateCartCount(data.cart_count);
            showNotification(data.message);
        } else {
            showNotification('Error updating cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating cart', 'error');
    });
}

function updateCartDisplay() {
    // Refresh the cart sidebar content
    fetch('get_cart_content.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('cart-content').innerHTML = html;
        })
        .catch(error => {
            console.error('Error updating cart display:', error);
        });
}

function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (count > 0) {
        if (cartCountElement) {
            cartCountElement.textContent = count;
        } else {
            // Create cart count element if it doesn't exist
            const cartContainer = document.querySelector('.cart-container');
            const countSpan = document.createElement('span');
            countSpan.className = 'cart-count';
            countSpan.id = 'cart-count';
            countSpan.textContent = count;
            cartContainer.appendChild(countSpan);
        }
    } else {
        if (cartCountElement) {
            cartCountElement.remove();
        }
    }
}

function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    // Create and show notification
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'error' ? '#dc3545' : '#28a745'};
        color: white;
        border-radius: 5px;
        z-index: 10000;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
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
    window.location.href = 'view_cart.html';
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
</script>

</body>
</html>