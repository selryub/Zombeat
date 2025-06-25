
document.addEventListener("DOMContentLoaded", () => {
    fetch("admin_employee.php")
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#EmployeeTable tbody");
            tbody.innerHTML = "";

            data.forEach(emp => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>${emp.full_name}</td>
                    <td>${emp.wage}</td>
                    <td>${emp.attendance_status}</td>
                `;

                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error("Error loading employee data:", error);
        });
});
