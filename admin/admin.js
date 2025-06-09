function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  if (sidebar.style.width === "270px") {
    sidebar.style.width = "0";
  } else {
    sidebar.style.width = "270px";
  }
}

document.addEventListener("click", function (e) {
  const sidebar = document.getElementById("sidebar");
  const menuIcon = document.querySelector(".menu-icon");

  if (
    sidebar.style.width === "270px" &&
    !sidebar.contains(e.target) &&
    !menuIcon.contains(e.target)
  ) {
    sidebar.style.width = "0";
  }
});



