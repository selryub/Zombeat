document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById("revExpChart").getContext("2d");

    // const revenue = <?=
    //     json_encode(array_column($daily, "revenue")); ?>;
    // const dates = <?=
    //     json_encode(array_column($daily, "date")); ?>;
    // const expenses = <?=
    //     json_encode(array_map(function($e) {
    //         return isset($e["expense"]) ? $e["expense"] : 0;
    //     }, $expense_data)); ?>;

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
});