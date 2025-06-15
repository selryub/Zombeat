document.addEventListener("DOMContentLoaded", () => {
  var ctx = document.getElementById("salesChart").getContext("2d");
  var salesChart = new Chart(ctx, {
    type: 'line',
    data: { 
      labels, 
      datasets: [{ 
        label: 'Sales (RM)', 
        data, 
        borderColor: '#4caf50', 
        fill: false }] },
    options: { 
      responsive: true, 
      maintainAspectRatio: false, 
      scales: { 
        y: { beginAtZero: true } } }
});
});

flatpickr("#calendar", {
  inline: true,
  defaultDate: new Date(),
  onChange: function(selectedDates, dateStr) {
    if (dateStr) {
      window.location.href = '?period=daily&date=' + dateStr;
    }
  }
});