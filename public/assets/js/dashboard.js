/**
 * Dashboard JavaScript
 * Handles KPI cards, employee table, and CRUD operations
 */

let allEmployees = [];
let roles = [];
let departments = [];

// Initialize dashboard on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchKpiData();
    fetchEmployees();
    loadRolesAndDepartments();
    
    // Start polling for KPI updates every 10 seconds
    setInterval(fetchKpiData, 10000);
    
    // Setup dropdown toggle
    setupDropdowns();
});

// Fetch KPI card data
async function fetchKpiData() {
    try {
        const data = await AdminAPI.getAllKpiData();
        
        document.getElementById('totalEmployees').textContent = data.total || 0;
        document.getElementById('checkedIn').textContent = data.checkedIn || 0;
        document.getElementById('checkedOut').textContent = data.checkedOut || 0;
        document.getElementById('absent').textContent = data.absent || 0;
    } catch (error) {
        console.error('Error fetching KPI data:', error);
    }
}

// Fetch all employees
async function fetchEmployees() {
    try {
        const data = await EmployeeAPI.getEmployees();
        allEmployees = data.employees || [];
        renderEmployees(allEmployees);
    } catch (error) {
        console.error('Error fetching employees:', error);
        document.getElementById('employeeTableBody').innerHTML = `
            <tr><td colspan="6" class="text-center text-danger">Error loading employees</td></tr>
        `;
        document.getElementById('mobileEmployees').innerHTML = `
            <div class="text-center text-danger py-4">Error loading employees</div>
        `;
    }
}

// Render employees in table and mobile view
function renderEmployees(employees) {
    // Desktop table
    const tableBody = document.getElementById('employeeTableBody');
    if (employees.length === 0) {
        tableBody.innerHTML = `
            <tr><td colspan="6" class="text-center text-muted">No employees found</td></tr>
        `;
    } else {
        tableBody.innerHTML = employees.map(emp => `
            <tr>
                <td>${escapeHtml(emp.name)}</td>
                <td>${emp.employeeId}</td>
                <td>${escapeHtml(emp.department)}</td>
                <td>${escapeHtml(emp.roles)}</td>
                <td>${escapeHtml(emp.lastClockIn)}</td>
                <td>${escapeHtml(emp.lastClockOut)}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-employee-id="${emp.id}">
                            ⁝
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" onclick="openEditModal(${emp.id})">Edit</button></li>
                            <li><button class="dropdown-item text-danger" onclick="confirmDelete(${emp.id}, '${escapeHtml(emp.name)}')">Delete</button></li>
                        </ul>
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    // Mobile cards
    const mobileContainer = document.getElementById('mobileEmployees');
    if (employees.length === 0) {
        mobileContainer.innerHTML = `
            <div class="text-center text-muted py-4">No employees found</div>
        `;
    } else {
        mobileContainer.innerHTML = employees.map(emp => `
            <div class="employee-card">
                <div class="card-header">
                    <div class="employee-info">
                        <h6 class="employee-name">${escapeHtml(emp.name)}</h6>
                        <span class="employee-id">${emp.employeeId}</span>
                    </div>
                    <div class="status-indicator">
                        <i class="fa-solid fa-circle-dot" style="color: ${emp.status === 'Active' ? '#00ffb3' : '#6c757d'}"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="employee-details">
                        <div class="detail-item">
                            <span class="label">Role:</span>
                            <span class="value">${escapeHtml(emp.roles)}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Department:</span>
                            <span class="value">${escapeHtml(emp.department)}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Status:</span>
                            <span class="value">${escapeHtml(emp.status)}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(${emp.id})">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(${emp.id}, '${escapeHtml(emp.name)}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // Reinitialize dropdowns
    setupDropdowns();
}

// Filter employees based on search
function filterEmployees() {
    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
    const filtered = allEmployees.filter(emp => 
        emp.name.toLowerCase().includes(searchQuery) ||
        String(emp.employeeId).includes(searchQuery) ||
        emp.department.toLowerCase().includes(searchQuery) ||
        emp.roles.toLowerCase().includes(searchQuery)
    );
    renderEmployees(filtered);
}

// Load roles and departments for dropdowns
async function loadRolesAndDepartments() {
    try {
        const [rolesData, deptData] = await Promise.all([
            EmployeeAPI.getRoles(),
            EmployeeAPI.getDepartments()
        ]);
        
        roles = rolesData.roles || [];
        departments = deptData.departments || [];
        
        populateDropdowns();
    } catch (error) {
        console.error('Error loading roles and departments:', error);
    }
}

// Populate role and department dropdowns
function populateDropdowns() {
    const roleSelects = document.querySelectorAll('#role, #editRole');
    const deptSelects = document.querySelectorAll('#department, #editDepartment');
    
    roleSelects.forEach(select => {
        select.innerHTML = '<option value="">Select Role</option>' +
            roles.map(role => `<option value="${escapeHtml(role)}">${escapeHtml(role)}</option>`).join('');
    });
    
    deptSelects.forEach(select => {
        select.innerHTML = '<option value="">Select Department</option>' +
            departments.map(dept => `<option value="${escapeHtml(dept)}">${escapeHtml(dept)}</option>`).join('');
    });
}

// Open add employee modal
function openAddEmployeeModal() {
    document.getElementById('addEmployeeForm').reset();
    document.getElementById('addEmployeeModal').classList.add('show');
}

// Close add employee modal
function closeAddEmployeeModal() {
    document.getElementById('addEmployeeModal').classList.remove('show');
}

// Add new employee
async function addEmployee(event) {
    event.preventDefault();
    
    const btn = document.getElementById('addEmployeeBtn');
    const originalText = btn.innerHTML;
    showLoading(btn);
    
    try {
        const formData = {
            first_name: document.getElementById('firstName').value,
            last_name: document.getElementById('lastName').value,
            id: document.getElementById('idNumber').value,
            contact_no: document.getElementById('contactNo').value,
            email: document.getElementById('email').value,
            address: document.getElementById('address').value,
            department: document.getElementById('department').value,
            roles: document.getElementById('role').value,
            user_type: document.getElementById('userType').value,
            date_hired: document.getElementById('dateHired').value || formatDate(new Date()),
            supervisor_name: document.getElementById('supervisorName').value,
            leave_balance: document.getElementById('leaveBalance').value || 0,
            status: document.getElementById('status').value
        };
        
        await EmployeeAPI.addEmployee(formData);
        
        showToast('Employee added successfully!', 'success');
        closeAddEmployeeModal();
        fetchEmployees();
        fetchKpiData();
    } catch (error) {
        console.error('Error adding employee:', error);
        showToast('Error adding employee: ' + error.message, 'error');
    } finally {
        hideLoading(btn, originalText);
    }
}

// Open edit modal
function openEditModal(employeeId) {
    const employee = allEmployees.find(emp => emp.id === employeeId);
    if (!employee) return;
    
    document.getElementById('editEmployeeId').value = employee.id;
    document.getElementById('editFirstName').value = employee.firstName || '';
    document.getElementById('editLastName').value = employee.lastName || '';
    document.getElementById('editIdNumber').value = employee.idNumber || '';
    document.getElementById('editContactNo').value = employee.contactNo || '';
    document.getElementById('editEmail').value = employee.email || '';
    document.getElementById('editAddress').value = employee.address || '';
    document.getElementById('editDepartment').value = employee.department || '';
    document.getElementById('editRole').value = employee.roles || '';
    document.getElementById('editUserType').value = employee.userType || 'Employee';
    document.getElementById('editDateHired').value = employee.dateHired || '';
    document.getElementById('editSupervisorName').value = employee.supervisorName || '';
    document.getElementById('editLeaveBalance').value = employee.leaveBalance || 0;
    document.getElementById('editStatus').value = employee.status || 'Active';
    
    document.getElementById('editEmployeeModal').classList.add('show');
}

// Close edit modal
function closeEditEmployeeModal() {
    document.getElementById('editEmployeeModal').classList.remove('show');
}

// Update employee
async function updateEmployee(event) {
    event.preventDefault();
    
    const btn = document.getElementById('updateEmployeeBtn');
    const originalText = btn.innerHTML;
    showLoading(btn);
    
    try {
        const formData = {
            employee_id: parseInt(document.getElementById('editEmployeeId').value),
            first_name: document.getElementById('editFirstName').value,
            last_name: document.getElementById('editLastName').value,
            id: document.getElementById('editIdNumber').value,
            contact_no: document.getElementById('editContactNo').value,
            email: document.getElementById('editEmail').value,
            address: document.getElementById('editAddress').value,
            department: document.getElementById('editDepartment').value,
            roles: document.getElementById('editRole').value,
            user_type: document.getElementById('editUserType').value,
            date_hired: document.getElementById('editDateHired').value,
            supervisor_name: document.getElementById('editSupervisorName').value,
            leave_balance: document.getElementById('editLeaveBalance').value,
            status: document.getElementById('editStatus').value
        };
        
        await EmployeeAPI.updateEmployee(formData);
        
        showToast('Employee updated successfully!', 'success');
        closeEditEmployeeModal();
        fetchEmployees();
    } catch (error) {
        console.error('Error updating employee:', error);
        showToast('Error updating employee: ' + error.message, 'error');
    } finally {
        hideLoading(btn, originalText);
    }
}

// Confirm delete
function confirmDelete(employeeId, employeeName) {
    if (confirm(`Are you sure you want to delete ${employeeName}?`)) {
        deleteEmployee(employeeId);
    }
}

// Delete employee
async function deleteEmployee(employeeId) {
    try {
        await EmployeeAPI.deleteEmployee(employeeId);
        
        showToast('Employee deleted successfully!', 'success');
        fetchEmployees();
        fetchKpiData();
    } catch (error) {
        console.error('Error deleting employee:', error);
        showToast('Error deleting employee: ' + error.message, 'error');
    }
}

// Setup dropdown toggles
function setupDropdowns() {
    document.querySelectorAll('.dropdown-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== this.nextElementSibling) {
                    menu.classList.remove('show');
                }
            });
            
            // Toggle this dropdown
            const menu = this.nextElementSibling;
            menu.classList.toggle('show');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    });
}

// Show user guide (placeholder)
// function showUserGuide() {
//     alert('User Guide functionality - to be implemented');
// }
