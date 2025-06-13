function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  if (sidebar.style.width === "250px") {
    sidebar.style.width = "0";
  } else {
    sidebar.style.width = "250px";
  }
}

let index = 0;
  const slides = document.querySelector('.slides');
  const totalSlides = document.querySelectorAll('.slide').length;

setInterval(() => {
  index = (index + 1) % totalSlides;
  slides.style.transform = `translateX(-${index * 100}%)`;
}, 3000); // change slide every 3 seconds

document.addEventListener("click", function (e) {
  const sidebar = document.getElementById("sidebar");
  const menuIcon = document.querySelector(".menu-icon");

  if (
    sidebar.style.width === "250px" &&
    !sidebar.contains(e.target) &&
    !menuIcon.contains(e.target)
  ) {
    sidebar.style.width = "0";
  }
});



