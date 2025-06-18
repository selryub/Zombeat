<?php
session_start();

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