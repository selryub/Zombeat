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

// Global search functionality
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("globalSearch");
  const blocks = document.querySelectorAll(".search-block");

  if (!searchInput) return;

  searchInput.addEventListener("input", function () {
    const searchTerm = searchInput.value.toLowerCase().trim();

    blocks.forEach(block => {
      const blockText = block.innerText.toLowerCase();
      const isMatch = blockText.includes(searchTerm);

      block.style.display = isMatch || searchTerm === "" ? "" : "none";
    });
  });
});