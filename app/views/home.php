<?php
require_once __DIR__ . '/../../includes/config.php';

$pageTitle = 'Dashboard - Clock It';
$currentPage = 'home';
$additionalJS = ['/assets/js/dashboard.js'];

require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Help Button -->
<button onclick="showUserGuide()" class="help-btn">
    <i class="fa-solid fa-circle-question"></i>
    Help Guide
</button>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <main class="dashboard-main" id="kpiCards">
        <!-- KPI Cards will be loaded here dynamically -->
        <div class="card">
            <div class="card-content">
                <i class="fa-solid fa-users card-icon"></i>
                <div>
                    <div class="card-title">Total Employees</div>
                    <div class="card-value" id="totalEmployees">0</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <i class="fa-solid fa-door-open card-icon"></i>
                <div>
                    <div class="card-title">Clock In</div>
                    <div class="card-value" id="checkedIn">0</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <i class="fa-solid fa-door-closed card-icon"></i>
                <div>
                    <div class="card-title">Clock Out</div>
                    <div class="card-value" id="checkedOut">0</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <i class="fa-solid fa-user-xmark card-icon"></i>
                <div>
                    <div class="card-title">Absent</div>
                    <div class="card-value" id="absent">0</div>
                </div>
            </div>
        </div>
    </main>

    <br><br>

    <!-- Employee Table Section -->
    <div class="search-add-container">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-primary small-btn" onclick="window.location.href='history.php'">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        History
                    </button>
                    <button class="btn btn-primary small-btn" onclick="window.location.href='timeLog.php'">
                        <i class="fa-solid fa-calendar"></i>
                        Time Log
                    </button>
                </div>
            </div>
            
            <div class="col">
                <div class="search-container">
                    <input 
                        type="text" 
                        class="form-control search-input" 
                        placeholder="Search employees..." 
                        id="searchInput"
                        oninput="filterEmployees()"
                    >
                </div>
            </div>
            
            <div class="col-auto">
                <button class="btn btn-primary small-btn" onclick="openAddEmployeeModal()">
                    <i class="fa-solid fa-plus"></i>
                    Add Employee
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="d-none d-md-block">
        <div class="table-responsive">
            <table class="employee-table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Employee ID</th>
                        <th scope="col">Department</th>
                        <th scope="col">Roles</th>
                        <th scope="col">Clock In</th>
                        <th scope="col">Clock Out</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            <i class="fa-solid fa-spinner fa-spin"></i> Loading employees...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="d-md-none mobile-employees-container" id="mobileEmployees">
        <div class="text-center text-muted py-4">
            <i class="fa-solid fa-spinner fa-spin"></i> Loading employees...
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal" id="addEmployeeModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="btn-close" onclick="closeAddEmployeeModal()"></button>
            </div>
            <div class="modal-body">
                <form id="addEmployeeForm" onsubmit="addEmployee(event)">
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-12">
                            <h6 class="section-title">Personal Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstName" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastName" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="idNumber" class="form-label">ID Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="idNumber" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="contactNo" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="contactNo" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" required>
                        </div>
                        
                        <!-- Employment Information -->
                        <div class="col-12">
                            <h6 class="section-title">Employment Information</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-select" id="department"></select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role"></select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="userType" class="form-label">User Type</label>
                            <select class="form-select" id="userType">
                                <option value="Employee">Employee</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="dateHired" class="form-label">Date Hired</label>
                            <input type="date" class="form-control" id="dateHired">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="supervisorName" class="form-label">Supervisor Name</label>
                            <input type="text" class="form-control" id="supervisorName">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="leaveBalance" class="form-label">Leave Balance</label>
                            <input type="number" class="form-control" id="leaveBalance" value="0" min="0">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="OnLeave">On Leave</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeAddEmployeeModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="addEmployeeBtn">Add Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal" id="editEmployeeModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Employee</h5>
                <button type="button" class="btn-close" onclick="closeEditEmployeeModal()"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm" onsubmit="updateEmployee(event)">
                    <input type="hidden" id="editEmployeeId">
                    <!-- Same fields as add form -->
                    <div class="row g-3">
                        <div class="col-12"><h6 class="section-title">Personal Information</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editFirstName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editLastName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ID Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editIdNumber" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="editContactNo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editAddress" required>
                        </div>
                        
                        <div class="col-12"><h6 class="section-title">Employment Information</h6></div>
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select class="form-select" id="editDepartment"></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Role</label>
                            <select class="form-select" id="editRole"></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">User Type</label>
                            <select class="form-select" id="editUserType">
                                <option value="Employee">Employee</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date Hired</label>
                            <input type="date" class="form-control" id="editDateHired">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Supervisor Name</label>
                            <input type="text" class="form-control" id="editSupervisorName">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Leave Balance</label>
                            <input type="number" class="form-control" id="editLeaveBalance" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="editStatus">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="OnLeave">On Leave</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeEditEmployeeModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateEmployeeBtn">Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    margin-top: 30px;
    background: #f8fafc;
    min-height: 100vh;
    padding: 120px 60px 50px;
}

/* Responsive Utilities */
.d-none {
    display: none !important;
}

.d-md-none {
    display: block !important;
}

.d-md-block {
    display: none !important;
}

@media (min-width: 768px) {
    .d-md-none {
        display: none !important;
    }
    
    .d-md-block {
        display: block !important;
    }
}

.col-auto {
    flex: 0 0 auto;
    width: auto;
}

.col {
    flex: 1 1 0%;
}

.align-items-center {
    align-items: center !important;
}
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
