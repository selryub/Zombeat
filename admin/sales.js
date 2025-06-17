document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('salesChart').getContext('2d');

    const labels = chartData.map(row => row.label);
    const revenueData = chartData.map(row => parseFloat(row.revenue));
    const ordersData = chartData.map(row => parseFloat(row.orders)); 

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Revenue (RM)',
                    data: revenueData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    yAxisID: 'y',
                },
                {
                    label: 'Total Orders',
                    data: ordersData,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                },
                title: {
                    display: true,
                    text: 'Revenue and Orders Overview'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            if (context.dataset.label === 'Revenue (RM)') {
                                return 'RM ' + context.formattedValue;
                            } else {
                                return context.formattedValue + ' orders';
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (RM)'
                    },
                    ticks: {
                        callback: function (value) {
                            return 'RM ' + value;
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Total Orders'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
});