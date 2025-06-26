<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FCSIT Kiosk</title>
  <link rel="stylesheet" href="index.css"/>
</head>
<body>
  
<div class="page-wrapper">
<!-- Sidebar -->
<!-- <div id="sidebar" class="sidebar">

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
</div> -->


<!-- Header -->
<header class="navbar">
  <div class="left-header">
    <img src="img/kiosk.jpg" alt="Logo" class="logo-img">
    <div class="logo-text">
      <span class="main-title">FCSIT KIOSK</span>
      <span class="sub-title">TMI 2104 WEB BASED SYSTEM  <br>
     DEVELOPMENT PROJECT DEMO</span>
    </div>
  </div>

  <nav>
    <a href="index.php">HOME</a>
    <a href="../REGISTERED MEMBER/user_dashboard.php">MENU</a>
    <a href="about.php">ABOUT</a>
    <a href="../REGISTERED MEMBER/review.php">REVIEWS</a>
  </nav>

  <div class="icons">
    <img src="img/cart.png" alt="cart" class="cart-img">
    <a href="login.php"><img src="img/account.png" alt="account" class="acc-img"></a>
    <span class="icon"></span>
  </div>
</header>



  <section class="slider">
<div class="slideshow-container">

  <div class="slide fade">
    <img src="img/vs2.png" alt="welcome">
  </div>

  <div class="slide fade">
    <img src="img/welcome.png" alt="vs1">
  </div>

  <div class="slide fade">
    <img src="img/vs1.png" alt="vs2">
  </div>

</div>

  </section>

  <section class="cards">
    <div class="card">
      <img src="img/SandwichRollEgg.png">
    </div>
    <div class="card">
      <img src="img/BuburAyam2.png">
    </div>
    <div class="card">
      <img src="img/ChickenWrap.png ">
    </div>
  </section>

<footer class="footer">
  <div class="contact">
    <p>CONTACT</p>
    <p>Exco Keusahawanan (Kiosk)
    <br>Persatuan Teknologi Maklumat (PERTEKMA)
    <br>Fakulti Sains Komputer dan Teknologi Maklumat
    <br>Universiti Malaysia Sarawak
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
// Toggle sidebar open and close
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");

  // If sidebar is open (250px), close it
  if (sidebar.style.width === "250px") {
    sidebar.style.width = "0";
  } 
  // Else, open it
  else {
    sidebar.style.width = "250px";
  }
}

// Auto-slide functionality for a carousel
let slideIndex = 0;
const slides = document.querySelectorAll('.slide');

function showSlides() {
  slides.forEach(slide => slide.classList.remove('active'));
  slideIndex = (slideIndex + 1) % slides.length;
  slides[slideIndex].classList.add('active');
}

showSlides(); // Show the first slide
setInterval(showSlides, 3000); // Change slide every 3 seconds

// Close sidebar if clicked outside it
document.addEventListener("click", function (e) {
  const sidebar = document.getElementById("sidebar");
  const menuIcon = document.querySelector(".menu-icon");

  // If sidebar is open AND click is outside sidebar AND menu icon
  if (
    sidebar.style.width === "250px" &&
    !sidebar.contains(e.target) &&
    !menuIcon.contains(e.target)
  ) {
    sidebar.style.width = "0"; // then close sidebar
  }
});
</script>

</body>
</html>
