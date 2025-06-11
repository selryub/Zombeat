document.getElementById('signupForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const inputs = this.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
  const [name, email, password, confirmPassword] = Array.from(inputs).map(input => input.value);

  if (password !== confirmPassword) {
    alert("Passwords do not match!");
    return;
  }

  if (!this.querySelector('#terms').checked) {
    alert("Please agree to the Terms & Conditions.");
    return;
  }

  alert("Account created successfully!");
  this.reset();
});

  const checkbox = document.getElementById("agree");
  const submitBtn = document.getElementById("submit-btn");

  checkbox.addEventListener("change", () => {
    submitBtn.disabled = !checkbox.checked;
  });