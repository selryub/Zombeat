document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("product-form");
  const addBtn = document.getElementById("add-product-btn");
  const popup = document.getElementById("product-form-popup");
  const overlay = document.getElementById("overlay");

  // Show popup and clear form for adding
  addBtn.addEventListener("click", () => {
    form.reset();
    document.getElementById("product_id").value = '';
    popup.style.display = "block";
    overlay.style.display = "block";
  });


  document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const card = btn.closest(".card");
      if (confirm("Remove this product from the screen?")) {
        card.remove();
      }
    });
  });
});

// Filter by category
function filterCategory(category) {
  const cards = document.querySelectorAll('.card');
  const categoryButtons = document.querySelectorAll('.category-btn');

  // Highlight active button
  categoryButtons.forEach(btn => btn.classList.remove('active'));
  event.target.classList.add('active');

  // Show/hide cards
  cards.forEach(card => {
    const cardCategory = card.getAttribute('data-category');
    if (category === 'all' || cardCategory === category) {
      card.classList.remove('hidden');
    } else {
      card.classList.add('hidden');
    }
  });
}
