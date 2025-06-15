<?php
include "db_connect.php";

// Handle deletion with foreign key check
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);

    // Check if product is used in order_item
    $check = $conn->prepare("SELECT COUNT(*) FROM order_item WHERE product_id = ?");
    $check->bind_param("i", $deleteId);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        echo "<script>alert('Cannot delete product. It is used in an order.'); window.location.href='admin_product.php';</script>";
        exit;
    }else {
        $conn->query("DELETE FROM product WHERE product_id = $deleteId");
        header("Location: admin_product.php");
        exit;
    }
}

/// Handle add/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['product_id'] ?? null;
    $name = $_POST['product_name'];
    $desc = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'] ?? '';

    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], "img/$image");
    }

    if ($id) {
        $sql = "UPDATE product SET product_name=?, description=?, category=?, price=?, image_url=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisi", $name, $desc, $category, $price, $image, $id);
    } else {
        $sql = "INSERT INTO product (product_name, description, category, price, image_url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssis", $name, $desc, $category, $price, $image);
    }
    $stmt->execute();
    header("Location: admin_product.php");
    exit;
}

$result = $conn->query("SELECT * FROM product");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin - Product Management</title>
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="adminstyle.css">
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
<?php include "admin_frame.php"; ?>
<button id="add-product-btn">+ Add Product</button>
<div class="items">
  <?php while($row = mysqli_fetch_assoc($result)): ?>
    <div class="card">
      <img src="img/<?= $row['image_url'] ?>" alt="">
      <p class="item-name"><?= htmlspecialchars($row['product_name']) ?></p>
      <div class="card-text">
        <p class="item-desc"><?= htmlspecialchars($row['description']) ?></p>
        <div class="price-button">
          <strong class="price">RM <?= number_format($row['price'], 2) ?></strong>
          <a href="?edit=<?= $row['product_id'] ?>">✎</a>
          <a href="?delete=<?= $row['product_id'] ?>" onclick="return confirm('Are you sure?')">🗑</a>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<div id="overlay"></div>
<div id="product-form-popup">
  <form id="product-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="product_id" id="product_id">
    <input type="text" name="product_name" placeholder="Product Name" required>
    <input type="text" name="category" placeholder="Category" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" name="price" placeholder="Price" step="0.01" required>
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
</script>
    <!--Link to JavaScript-->
    <script src="product.js"></script>
    <script src="admin.js"></script>
</body>
</html>

