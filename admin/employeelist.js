document.addEventListener('DOMContentLoaded', ()=> {
    fetch('admin_employee.php')
    .then(res => res.json())
    .then(data => populateTable(data.employees))
    .catch(err => console.error('Error:', err));
});

function populateTable(employees) {
    const tbody = document.querySelector('#EmployeeTable tbody');
    employees.forEach(emp => {
        const tr = document.createElement("tr");S
        tbody.appendChild(tr);
    });
}

const backBtn = document.getElementById('back-btn');
let currentPage = 1;

function loadPage(page) {
    console.log('Load page ${page}');
    currentPage = page;
}
backBtn.addEventListener('click', ()=> {
    if (currentPage > 1) loadPage (currentPage - 1);
});