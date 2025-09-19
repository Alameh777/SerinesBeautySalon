document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('services_table');
    let counter = table.querySelectorAll('.service-row').length;

    function updateEmployeeOptions(row) {
        const serviceId = Number(row.querySelector('.service_select').value);
        const employeeSelect = row.querySelector('.employee_select');
        const currentValue = employeeSelect.value;
        employeeSelect.innerHTML = '<option value="">--Select Employee--</option>';
        employees.forEach(emp => {
            if (emp.services.some(s => s.id == serviceId)) {
                const opt = document.createElement('option');
                opt.value = emp.id;
                opt.text = emp.name;
                if (emp.id == currentValue) opt.selected = true;
                employeeSelect.add(opt);
            }
        });
    }

    // initialize existing rows
    table.querySelectorAll('.service-row').forEach(row => {
        row.querySelector('.service_select').addEventListener('change', () => updateEmployeeOptions(row));
        row.querySelector('.remove-btn').addEventListener('click', () => row.remove());
    });

    document.getElementById('add_service').addEventListener('click', () => {
        const tbody = table.querySelector('tbody');
        const newRow = tbody.querySelector('.service-row').cloneNode(true);
        newRow.querySelectorAll('select').forEach(sel => sel.value = '');
        tbody.appendChild(newRow);

        newRow.querySelector('.service_select').addEventListener('change', () => updateEmployeeOptions(newRow));
        newRow.querySelector('.remove-btn').addEventListener('click', () => newRow.remove());

        counter++;
    });
});
