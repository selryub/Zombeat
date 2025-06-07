document.addEventListener("DOMContentLoaded", () => {
    const editBtn = document.getElementById("edit-btn");
    const cancelBtn = document.getElementById("cancel-btn");
    const form = document.getElementById("edit-form");
    //const profileBox = document.querySelector(".admin-profile-box");

    editBtn.addEventListener("click", () => {
        form.style.display = "block";
        editBtn.style.display = "none";
    });

    cancelBtn.addEventListener("click", () => {
        form.style.display = "none";
        editBtn.style.display = "inline-block";
    });
});