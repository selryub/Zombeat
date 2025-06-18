<?php
session_start();

// If user is logged in, redirect to registered menu
if (isset($_SESSION['user_id'])) {
    header('Location: /Zombeat/REGISTERED MEMBER/order.php');
    exit;
}

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
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FCSIT Kiosk - Order Page</title>
  <link rel="stylesheet" href="index.css" />
  <link rel="stylesheet" href="order.css" />
</head>
<body>

<div class="page-wrapper">
<!-- Sidebar -->
  <div id="sidebar" class="sidebar">

  <a href="otherpage.html">
  <img src="img/account.png" alt="Clickable Image Button"  class="acc-dash">
  <p class = "hellouser">HELLO USER !</p>
  </a>

  <a href="menu_page.html" class="menuall">
    <img src="img/layout.png" class="menu">
    <span class="dash-text">MENU</span>
  </a>

  <a href="#">
    <img src="img/list.png" class="orders">
    <span class="dash-text">ORDERS</span>
  </a>

  <a href="#">
    <img src="img/card plus.png" class="billing">
    <span class="dash-text">BILLING</span>
  </a>

  <a href="#">
    <img src="img/gps.png" class="trackOrders">
    <span class="dash-text">TRACK ORDERS</span>
  </a>

  <a href="#">
    <img src="img/profile2.png" class="profile">
    <span class="dash-text">PROFILE</span>
  </a>

  <a href="#">
      <img src="img/logout.png" class="logout">
  <span class="dash-text">LOGOUT</span>
  </a>
</div>

<!-- Header -->
<header class="navbar">
<div class="left-header">
  <!-- <div class="menu-icon" onclick="toggleSidebar()">☰</div> -->
  <a href="index.php">
  <img src="img/kiosk.jpg" alt="Logo" class="logo-img">
  </a>
  <div class="logo-text">FCSIT KIOSK</div>
</div>

  <nav>
    <a href="index.php">HOME</a>
    <a href="menu_page.php">MENU</a>
    <a href="about.php">ABOUT</a>
    <a href="review.html">REVIEWS</a>
  </nav>
  <div class="icons">
    <img src="img/cart.png" alt="cart" class="cart-img">
    <a href="/Zombeat/REGISTER MEMBER/login.php"><img src="img/account.png" alt="account" class="acc-img"></a>
    <span class="icon"></span>
  </div>
</header>

<div class="menu-header-bar">
  <h2>MENU</h2>
  <form method="GET" action="menu_page.php">
  <input type="text" name="search" class="search-menu" placeholder=" 🔍︎ Search" value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
</form>
</div>

<div class="menu-container">
  <div class="menu-text">
<div class="categories">
<?php
$categories = ['ALL', 'HEAVY FOODS', 'SNACKS', 'DRINKS'];
foreach ($categories as $cat) {
    $activeClass = ($cat === $category) ? 'active-category' : '';
    echo "<a href='?category=" . urlencode($cat) . "'><span class='$activeClass'>$cat</span></a>";
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
                    <button>+</button>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No items found in this category.</p>
<?php endif; ?>
</div>
</div>
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
  <img src="img/blackline.png">
</div>

<div class="hours">
  <p>OPENING HOURS</p>
  <p>MON - FRI: 8AM - 6PM</p>
  <p>SATURDAY & SUNDAY: CLOSED</p>
</div>
</footer>

<script>
function addToCart(productId, productName, price) {
    fetch('cart_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add_to_cart&product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`${productName} added to cart!`);
        } else {
            alert(data.message || 'Error adding item to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding item to cart');
    });
}

function viewCart() {
    window.location.href = 'cart.php';
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', function () {
        alert('Please login first to add items to your cart.');
        window.location.href = '/Zombeat/REGISTERED MEMBER/login.php';
    });
});
</script>
</body>
</html>