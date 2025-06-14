document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById("salesChart");
    if(!canvas) {
        console.error("Canvas element not found!");
        return;
    }
    
    const ctx = canvas.getContext("2d");

    new Chart(ctx, {
        type: "line",
        data: { 
            labels: chartDates, 
            datasets: [{ 
                label: 'Revenue (RM)',
                data: chartRevenue, 
                borderColor: '#42A5F5', fill: false 
            }] 
        },
        options: { 
            responsive: true, 
            scales: { 
                x: { display: true }, 
                y: { beginAtZero: true }
            } 
        }
  });
});