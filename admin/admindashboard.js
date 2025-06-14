document.addEventListener('DOMContentLoaded', () => {
  const canvas = document.getElementById('salesChart');
  if (!canvas || !Array.isArray(labels) || labels.length === 0) {
    console.warn('No chart data or canvas missing');
    return;
  }
  const ctx = canvas.getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: { labels, datasets: [{ label: 'Sales (RM)', data, borderColor: '#4caf50', fill: false }] },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
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