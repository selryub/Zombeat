
document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById('financialChart').getContext('2d');

  const labels = chartData.map(item => item.label); 
  const itemsSold = chartData.map(item => parseInt(item.items_sold));
  const revenue = chartData.map(item => parseFloat(item.revenue));
  const profit = chartData.map(item => parseFloat(item.items_sold) * 0.20); 

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Items Sold',
          data: itemsSold,
          backgroundColor: 'rgba(54, 54, 235, 0.3)',
          yAxisID: 'y',
          type: 'bar'
        },
        {
          label: 'Revenue (RM)',
          data: revenue,
          borderColor: 'rgba(255, 99, 132, 1)',
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          yAxisID: 'y1',
          type: 'line',
          tension: 0.4
        },
        {
          label: 'Profit (RM)',
          data: profit,
          borderColor: 'rgba(75, 192, 75, 1)',
          backgroundColor: 'rgba(34, 139, 34, 0.2)',
          yAxisID: 'y1',
          type: 'line',
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      stacked: false,
      plugins: {
        title: {
          display: true,
          text: `Financial Performance (${period.charAt(0).toUpperCase() + period.slice(1)})`
        }
      },
      scales: {
        y: {
          type: 'linear',
          position: 'left',
          title: { display: true, text: 'Items Sold' }
        },
        y1: {
          type: 'linear',
          position: 'right',
          title: { display: true, text: 'Revenue / Profit (RM)' },
          grid: { drawOnChartArea: false }
        }
      }
    }
  });

  async function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const content = document.querySelector('.content');

    await html2canvas(content).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgProps = doc.getImageProperties(imgData);
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        doc.save("financial-report.pdf");
  });
}
});