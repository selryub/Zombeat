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

document.addEventListener("DOMContentLoaded", () => {
  const setPanel = document.getElementById('settings-panel');
  const toggleBtn = document.getElementById('settings-toggle');
  const themeSelect = document.getElementById('theme-switch');

  //Show-Hide dropdown
  toggleBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    setPanel.style.display = setPanel.style.display === "block" ? "none" : "block";
  });

  //Apply saved theme
  const savedTheme = localStorage.getItem("theme") || "light";
  applyTheme(savedTheme);
  themeSelect.value = savedTheme;
  
  //Theme switch
  themeSelect.addEventListener("change", () => {
    const theme = themeSelect.value;
    applyTheme(selected);
    localStorage.setItem("theme", selected);
  });

  function applyTheme(theme) {
    if (theme === "dark") {
      document.body.classList.add("dark-mode");
    } else {
      document.body.classList.remove("dark-mode");
    }
  }
  
  //Close dropdown
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".settings-container")) {
          setPanel.style.display = 'none';
    }
  });

});

