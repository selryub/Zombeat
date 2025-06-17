<?php
// feedback.php - handles rating & feedback submission

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $feedback = isset($_POST['feedback']) ? htmlspecialchars(trim($_POST['feedback'])) : '';

    // Simple validation and processing (to be extended: save to DB)
    if ($rating > 0 && !empty($feedback)) {
        $message = 'Thank you for your feedback!';
        // Here you can add code to save data to DB
    } else {
        $message = 'Please provide a rating and feedback.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>FCSIT Kiosk - Feedback</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
  <?php include "styles.css"; ?>
</style>
</head>
<body>
  <header>
    <div class="nav-container">
      <button class="menu-toggle">&#9776;</button>
      <img src="logo.png" alt="FCSIT Kiosk" class="logo" />
      <nav>
        <a href="order.php">HOME</a>
        <a href="#">MENU</a>
        <a href="#">ABOUT</a>
        <a href="feedback.php" class="active">REVIEWS</a>
      </nav>
      <div class="icons">
        <input type="search" placeholder="Search" />
        <a href="#"><i class="fa fa-shopping-cart"></i></a>
        <a href="#"><i class="fa fa-user"></i></a>
      </div>
    </div>
  </header>

  <main class="feedback-main">
    <section class="rating-feedback-box">
      <h2>RATING &amp; FEEDBACK</h2>
      <form method="post" action="">
        <label for="rating">Rate your meal experience:</label><br />
        <div class="stars">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php if(isset($rating) && $rating == $i) echo 'checked'; ?> />
            <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> star">&#9733;</label>
          <?php endfor; ?>
        </div>
        <label for="feedback">Write your feedback:</label><br />
        <textarea id="feedback" name="feedback" rows="5" placeholder="Type here"><?php echo isset($feedback) ? $feedback : ''; ?></textarea><br />
        <button type="submit">SUBMIT</button>
      </form>
      <?php if ($message): ?>
        <p class="msg"><?php echo $message; ?></p>
      <?php endif; ?>
    </section>

    <section class="reviews-display">
      <h3>What others are saying:</h3>
      <article class="review">
        <i class="fa fa-user-circle review-icon"></i>
        <div class="review-stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut a pulvinar orci. Duis at pharetra quam.</p>
      </article>
      <article class="review">
        <i class="fa fa-user-circle review-icon"></i>
        <div class="review-stars">
          <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-alt"></i>
        </div>
        <p>Integer in pharetra erat, sit amet convallis arcu. Maecenas dignissim lorem sed nunc sodales, non tempus metus tristique.</p>
      </article>
      <article class="review">
        <i class="fa fa-user-circle review-icon"></i>
        <div class="review-stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
        <p>Nam condimentum neque nec ipsum aliquet, in dignissim justo tristique. Curabitur id ullamcorper lectus.</p>
      </article>
    </section>
  </main>
</body>
</html>