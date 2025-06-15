document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById("revExpChart").getContext("2d");

    new Chart(ctx, {
        type: "line",
        data: {
            labels: dates,
            datasets: [
                { label: "Revenue", data: revenue, borderColor: "#4caf50", fill: false},
                { label: "Expenses", data: expenses, borderColor: "#f44336", fill: false},
            ]
        },
        options: {
            responsive: true,
            interaction: {mode: "index", intersect: false},
            scales: {
                x: { type: "time", time: {parser: "YYYY-MM-DD", unit: "day", displayFormats: "DD MMM"}},
                y: { beginAtZero: true}
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

