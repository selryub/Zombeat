<?php

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

// Include the header with cart functionality
include 'regmem_frame.php';
?>

<!-- Additional CSS for menu page specific styling -->
<link rel="stylesheet" href="../PUBLIC/menu_page.css" />

<div class="page-wrapper">

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

</body>
</html>