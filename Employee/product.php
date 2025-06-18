<?php
include "../admin/db_connect.php";

// Handle soft delete
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $conn->query("UPDATE product SET is_active = 0 WHERE product_id = $deleteId");
    header('Location: product.php');
    exit;
}

// Handle add/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['product_id'] ?? null;
    $name = $_POST['product_name'];
    $desc = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    $image = $_FILES['image']['name'] ?? '';
    $image_tmp = $_FILES['image']['tmp_name'] ?? '';
    $finalImage = '';

    // Handle file upload
    if ($image) {
        $targetDir = "img/";
        $finalImage = basename($image);
        move_uploaded_file($image_tmp, $targetDir . $finalImage);
    }

    if ($id) {
        // Update: if no new image uploaded, keep old image
        if ($image) {
            $sql = "UPDATE product SET product_name=?, description=?, category=?, price=?, image_url=? WHERE product_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssisi", $name, $desc, $category, $price, $finalImage, $id);
        } else {
            $sql = "UPDATE product SET product_name=?, description=?, category=?, price=? WHERE product_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdi", $name, $desc, $category, $price, $id);
        }
    } else {
        // Insert new
        $sql = "INSERT INTO product (product_name, description, category, price, image_url, is_active) VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssis", $name, $desc, $category, $price, $finalImage);
    }

    $stmt->execute();
    header("Location: product.php");
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
    <title>Admin - Product Management</title>
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
<button id="add-product-btn" class="add-product-btn">+ Add Product</button>
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
      <div class="card-text">
        <p class="item-desc"><?= htmlspecialchars($row['description']) ?></p>
        <div class="price-button">
          <strong class="price">RM <?= number_format($row['price'], 2) ?></strong>
          <div class="action-icons">
            <button class="icon-btn edit" onclick="editProduct(<?php echo htmlspecialchars(json_encode($row)); ?>)">✎</button>
            <a class="icon-btn delete" href="?delete=<?= $row['product_id'] ?>" onclick="return confirm('Are you sure?')">🗑</a>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<div id="overlay"></div>
<div id="product-form-popup">
  <form id="product-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="product_id" id="product_id">
    <input type="text" name="product_name" id="product_name" placeholder="Product Name" required>
    <input type="text" name="category" id="category" placeholder="Category" required>
    <textarea name="description" id="description" placeholder="Description" required></textarea>
    <input type="number" name="price" id="price" placeholder="Price" step="0.01" required>
    <input type="file" name="image">
    <button type="submit">Save Product</button>
    <button type="button" onclick="closeForm()">Cancel</button>
  </form>
</div>

<script>
function openForm() {
  document.getElementById('product-form-popup').style.display = 'block';
  document.getElementById('overlay').style.display = 'block';
}
function closeForm() {
  document.getElementById('product-form-popup').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}
document.getElementById('add-product-btn').addEventListener('click', openForm);
function editProduct(data) {
    openForm();
    document.getElementById('product_id').value = data.product_id;
    document.getElementById('product_name').value = data.product_name;
    document.getElementById('description').value = data.description;
    document.getElementById('category').value = data.category;
    document.getElementById('price').value = data.price;
}
</script>
    <!--Link to JavaScript-->
    <script src="product.js"></script>
    <script src="employee.js"></script>
</body>
</html>
