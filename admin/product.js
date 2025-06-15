document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("product-form");
  const addBtn = document.getElementById("add-product-btn");

  // Show form for new product
  addBtn.addEventListener("click", () => {
    form.reset();
    form.style.display = "block";
  });

  // Edit existing
  document.querySelectorAll(".edit-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const card = btn.closest(".card");
      const id = card.dataset.id;
      // Optionally use AJAX to get product info by ID
      form.product_id.value = id;
      form.name.value = card.querySelector(".item-name").innerText;
      form.description.value = card.querySelector(".item-desc").innerText;
      form.price.value = card.querySelector(".price").innerText.replace("RM", "").trim();
      form.style.display = "block";
    });
  });

  // Delete product
  document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.closest(".card").dataset.id;
      if (confirm("Delete this product?")) {
        fetch(`delete_product.php?id=${id}`, { method: "GET" })
          .then(() => location.reload());
      }
    });
  });
});
