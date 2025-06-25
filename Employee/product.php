<?php
include "../admin/db_connect.php";

// Handle purchase (stock -1)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['purchase_id'])) {
    $productId = intval($_POST['purchase_id']);

    // Prevent negative stock
    $stmt = $conn->prepare("UPDATE product SET stock_quantity = stock_quantity - 1 WHERE product_id = ? AND stock_quantity > 0");
    $stmt->bind_param("i", $productId);
    $stmt->execute();

    // Optional: Add logging here for record

    // Redirect to avoid form resubmission
    header("Location: product.php?category=" . urlencode($_GET['category'] ?? 'ALL') . "&search=" . urlencode($_GET['search'] ?? ''));
    exit;
}
$result = $conn->query("SELECT * FROM product WHERE is_active = 1");

// Get category and search input from URL
$category = $_GET['category'] ?? 'ALL';
$search = $_GET['search'] ?? '';

// Start building SQL query
$sql = "SELECT * FROM product WHERE is_active=1";
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
    <title>Employee - Product Management</title>
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="employeestyle.css">
    <style>
    #product-form-popup {
      display: none;
      position: fixed;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      background: #fff;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      border-radius: 10px;
      z-index: 999;
    }
    #overlay {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 998;
    }
  </style>
</head>
<body>
<?php include "employee_frame.php"; ?>
<div class="menu-header-bar">
  <h2>PRODUCT</h2>
  <form method="GET" action="product.php">
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
  <?php while($row = mysqli_fetch_assoc($result)): ?>
    <div class="card">
      <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="">
      <p class="item-name"><?= htmlspecialchars($row['product_name']) ?></p>
      <div class="middle-row">
        <small>Stock: <?= htmlspecialchars($row['stock_quantity']) ?></small>
        <form method="POST" action="" onsubmit="return confirm('Confirm purchase?')">
              <input type="hidden" name="purchase_id" value="<?= $row['product_id'] ?>">
                      <button type="submit" class="icon-btn purchase">+</button>
            </form>

      </div>
      <div class="card-text">
        <p class="item-desc"><?= htmlspecialchars($row['description']) ?></p>
        <div class="price-button">
          <strong class="price">RM <?= number_format($row['price'], 2) ?></strong>
          <div class="action-icons">
            <form method="POST" action="" onsubmit="return confirm('Confirm purchase?')">
              <input type="hidden" name="purchase_id" value="<?= $row['product_id'] ?>">
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>


</script>
    <!--Link to JavaScript-->
    <script src="product.js"></script>
    <script src="employee.js"></script>
</body>
</html>
