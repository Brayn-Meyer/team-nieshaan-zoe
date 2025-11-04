<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Custom styles matching the Vue component */
        .search-add-container {
            max-width: 100%;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .search-add-container {
                max-width: 95%;
            }
        }

        @media (min-width: 1200px) {
            .search-add-container {
                max-width: 90%;
            }
        }

        /* Small button styles */
        .small-btn {
            padding: 6px 12px;
            font-size: 0.875rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Search Container */
        .search-container {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #2EB28A;
            border-radius: 8px;
            background-color: #f8f9fa;
        }

        .search-input {
            width: 100%;
            border: 1px solid #2EB28A;
            font-size: 0.875rem;
            padding: 6px 12px;
        }

        .search-input:focus {
            border-color: #2EB28A;
            box-shadow: 0 0 0 0.2rem rgba(0, 192, 170, 0.25);
        }

        /* Button Styles */
        .btn-primary {
            background-color: #2EB28A;
            border-color: #2EB28A;
        }

        .btn-primary:hover {
            background-color: #00a895;
            border-color: #00a895;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        /* Desktop Table View */
        .table-responsive {
            overflow-x: auto;
            border-radius: 8px;
        }

        .employee-table {
            margin: 0 auto;
            width: 95%;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-collapse: separate;
            border-spacing: 0;
        }

        .employee-table thead th:first-child {
            border-top-left-radius: 8px;
        }

        .employee-table thead th:last-child {
            border-top-right-radius: 8px;
        }

        .employee-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 8px;
        }

        .employee-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 8px;
        }

        .employee-table th,
        .employee-table td {
            border: none;
        }

        .employee-table thead {
            background-color: #2EB28A !important;
        }

        th {
            padding: 1.5rem;
            font-weight: 600;
            color: #FAFAFA;
        }

        /* Modal Styles */
        .modal-xl {
            max-width: 1140px;
        }

        .section-title {
            color: #2EB28A;
            font-weight: 600;
            border-bottom: 2px solid #2EB28A;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        /* Form Styles */
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 1px solid #2EB28A;
            border-radius: 6px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2EB28A;
            box-shadow: 0 0 0 0.2rem rgba(0, 192, 170, 0.25);
        }

        .form-check-input:checked {
            background-color: #2EB28A;
            border-color: #00C0AA;
        }

        /* Password Input Group */
        .password-input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            border: none;
            background: transparent;
            border-left: 1px solid #2EB28A;
            border-radius: 0 6px 6px 0;
        }

        .password-toggle:hover {
            background-color: #f8f9fa;
        }

        /* User Type Container */
        .user-type-container {
            padding: 0.5rem 0;
        }

        /* Mobile Card View */
        .mobile-employees-container {
            padding: 0 10px;
        }

        .employee-card {
            background: white;
            border: 1px solid #2EB28A;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .employee-info h6 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .employee-id {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .status-indicator {
            font-size: 0.75rem;
        }

        .card-body {
            padding: 15px;
        }

        .employee-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
        }

        .detail-item .label {
            font-weight: 500;
            color: #6c757d;
        }

        .detail-item .value {
            color: #333;
        }

        .card-footer {
            padding: 15px;
            border-top: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .action-buttons .btn {
            padding: 8px 12px;
            border-radius: 6px;
        }

        /* Modal Responsiveness */
        .modal-dialog {
            margin: 1rem;
        }

        @media (min-width: 576px) {
            .modal-dialog {
                margin: 1.75rem auto;
            }
        }

        .modal-content {
            border: 1px solid #2EB28A;
        }

        .modal-header {
            border-bottom: 1px solid #2EB28A;
            padding: 1rem 1rem;
        }

        .modal-body {
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-footer {
            border-top: 1px solid #2EB28A;
            padding: 1rem;
        }

        /* Badge Styles */
        .badge {
            font-size: 0.75em;
            padding: 0.35em 0.65em;
        }

        /* Text and spacing */
        .text-muted {
            color: #6c757d !important;
        }

        .form-text {
            font-size: 0.875em;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        button.btn.btn-secondary {
            background-color: white;
            border: none;
            color: #6c757d;
        }

        button.btn.btn-secondary:hover {
            color: #00C0AA;
        }

        .dropdown-item {
            cursor: pointer;
            padding: 0.5rem 1rem;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-outline-primary {
            color: #2EB28A;
            border-color: #2EB28A;
        }

        .btn-outline-primary:hover {
            background-color: #2EB28A;
            border-color: #2EB28A;
            color: white;
        }

        .btn-outline-info {
            color: #2EB28A;
            border-color: #2EB28A;
        }

        .btn-outline-info:hover {
            background-color: #2EB28A;
            border-color: #2EB28A;
            color: white;
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        /* Responsive adjustments for the form */
        @media (max-width: 768px) {
            .modal-body {
                padding: 1rem;
                max-height: 60vh;
            }
            
            .section-title {
                font-size: 1rem;
            }
            
            .small-btn {
                font-size: 0.8rem;
                padding: 5px 8px;
            }
            
            .search-container {
                padding: 6px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Search and Add Container -->
        <div class="search-add-container mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <div class="d-flex gap-2">
                        <a href="/history" class="btn btn-primary small-btn">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            History
                        </a>
                        <a href="/Timelog" class="btn btn-primary small-btn">
                            <i class="fa-solid fa-calendar"></i>
                            Time log
                        </a>
                        <!-- AdminNotifications would go here -->
                    </div>
                </div>
                
                <div class="col">
                    <div class="search-container">
                        <input 
                            type="text" 
                            class="form-control search-input" 
                            placeholder="Search employees..." 
                            id="searchQuery"
                        >
                    </div>
                </div>
                
                <div class="col-auto">
                    <button class="btn btn-primary small-btn" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        <i class="fa-solid fa-plus me-1"></i>
                        Add Employee
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile View -->
        <div class="d-md-none mobile-employees-container" id="mobileEmployeesContainer">
            <!-- Employee cards will be populated here by JavaScript -->
        </div>
        
        <!-- Desktop View -->
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table employee-table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Employee ID</th>
                            <th scope="col">Department</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="employeesTableBody">
                        <!-- Employee rows will be populated here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Employee Modal -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addEmployeeForm">
                            <div class="row g-3">
                                <!-- Personal Information -->
                                <div class="col-12">
                                    <h6 class="section-title">Personal Information</h6>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="employeeId" class="form-label">Employee ID <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="employeeId" 
                                        required
                                    >
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="classificationId" class="form-label">Classification ID <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="classificationId" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="firstName" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="lastName" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="contactNo" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input 
                                        type="tel" 
                                        class="form-control" 
                                        id="contactNo" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="email" 
                                        required
                                    >
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea 
                                        class="form-control" 
                                        id="address" 
                                        required
                                        placeholder="Enter full address"
                                        rows="3"
                                    ></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label for="idNumber" class="form-label">ID Number <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="idNumber" 
                                        required
                                    >
                                </div>

                                <!-- Employment Information -->
                                <div class="col-12 mt-4">
                                    <h6 class="section-title">Employment Information</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="dateHired" class="form-label">Date Hired <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="dateHired" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="supervisorName" class="form-label">Supervisor Name</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="supervisorName" 
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="leaveBalance" class="form-label">Leave Balance <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="leaveBalance" 
                                        required
                                        min="0"
                                        value="20"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">User Type <span class="text-danger">*</span></label>
                                    <div class="user-type-container">
                                        <div class="form-check form-check-inline">
                                            <input 
                                                class="form-check-input" 
                                                type="radio" 
                                                id="userTypeEmployee" 
                                                value="Employee" 
                                                name="userType"
                                                checked
                                            >
                                            <label class="form-check-label" for="userTypeEmployee">Employee</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input 
                                                class="form-check-input" 
                                                type="radio" 
                                                id="userTypeAdmin" 
                                                value="Admin" 
                                                name="userType"
                                            >
                                            <label class="form-check-label" for="userTypeAdmin">Admin</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Login Credentials -->
                                <div class="col-12 mt-4">
                                    <h6 class="section-title">Login Credentials</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="username" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="password-input-group">
                                        <input 
                                            type="password" 
                                            class="form-control" 
                                            id="password" 
                                            required
                                            placeholder="Enter password"
                                        >
                                        <button 
                                            type="button" 
                                            class="btn btn-outline-secondary password-toggle"
                                            onclick="togglePasswordVisibility('password')"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Roles and Department -->
                                <div class="col-12 mt-4">
                                    <h6 class="section-title">Roles & Department</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="roles" class="form-label">Roles <span class="text-danger">*</span></label>
                                    <select class="form-select" id="roles" required>
                                        <option value="" disabled selected>Select a role</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Developer">Developer</option>
                                        <option value="Designer">Designer</option>
                                        <option value="Analyst">Analyst</option>
                                        <option value="Administrator">Administrator</option>
                                        <option value="Supervisor">Supervisor</option>
                                        <option value="Coordinator">Coordinator</option>
                                        <option value="unemployed">Unemployed</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                                    <select class="form-select" id="department" required>
                                        <option value="" disabled selected>Select a department</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Design">Design</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Sales">Sales</option>
                                        <option value="HR">Human Resources</option>
                                        <option value="Finance">Finance</option>
                                        <option value="Operations">Operations</option>
                                        <option value="IT">Information Technology</option>
                                        <option value="unemployed">Unemployed</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="addNewEmployee()">Add Employee</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Employee Modal -->
        <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editEmployeeForm">
                            <div class="row g-3">
                                <!-- Personal Information -->
                                <div class="col-12">
                                    <h6 class="section-title">Personal Information</h6>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="editEmployeeId" class="form-label">Employee ID <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="editEmployeeId" 
                                        required
                                    >
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="editClassificationId" class="form-label">Classification ID <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="editClassificationId" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="editFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="editFirstName" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="editLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="editLastName" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="editContactNo" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input 
                                        type="tel" 
                                        class="form-control" 
                                        id="editContactNo" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="editEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="editEmail" 
                                        required
                                    >
                                </div>

                                <div class="col-12">
                                    <label for="editAddress" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea 
                                        class="form-control" 
                                        id="editAddress" 
                                        required
                                        placeholder="Enter full address"
                                        rows="3"
                                    ></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label for="editIdNumber" class="form-label">ID Number <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="editIdNumber" 
                                        required
                                    >
                                </div>

                                <!-- Employment Information -->
                                <div class="col-12 mt-4">
                                    <h6 class="section-title">Employment Information</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="editDateHired" class="form-label">Date Hired <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="editDateHired" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="editSupervisorName" class="form-label">Supervisor Name</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="editSupervisorName" 
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="editLeaveBalance" class="form-label">Leave Balance <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="editLeaveBalance" 
                                        required
                                        min="0"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">User Type <span class="text-danger">*</span></label>
                                    <div class="user-type-container">
                                        <div class="form-check form-check-inline">
                                            <input 
                                                class="form-check-input" 
                                                type="radio" 
                                                id="editUserTypeEmployee" 
                                                value="Employee" 
                                                name="editUserType"
                                            >
                                            <label class="form-check-label" for="editUserTypeEmployee">Employee</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input 
                                                class="form-check-input" 
                                                type="radio" 
                                                id="editUserTypeAdmin" 
                                                value="Admin" 
                                                name="editUserType"
                                            >
                                            <label class="form-check-label" for="editUserTypeAdmin">Admin</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Login Credentials -->
                                <div class="col-12 mt-4">
                                    <h6 class="section-title">Login Credentials</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="editUsername" class="form-label">Username <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="editUsername" 
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="editPassword" class="form-label">Password</label>
                                    <div class="password-input-group">
                                        <input 
                                            type="password" 
                                            class="form-control" 
                                            id="editPassword" 
                                            placeholder="Leave blank to keep current password"
                                        >
                                        <button 
                                            type="button" 
                                            class="btn btn-outline-secondary password-toggle"
                                            onclick="togglePasswordVisibility('editPassword')"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Leave password field blank to keep the current password</div>
                                </div>

                                <!-- Roles and Department -->
                                <div class="col-12 mt-4">
                                    <h6 class="section-title">Roles & Department</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="editRoles" class="form-label">Roles <span class="text-danger">*</span></label>
                                    <select class="form-select" id="editRoles" required>
                                        <option value="" disabled>Select a role</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Developer">Developer</option>
                                        <option value="Designer">Designer</option>
                                        <option value="Analyst">Analyst</option>
                                        <option value="Administrator">Administrator</option>
                                        <option value="Supervisor">Supervisor</option>
                                        <option value="Coordinator">Coordinator</option>
                                        <option value="unemployed">Unemployed</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="editDepartment" class="form-label">Department <span class="text-danger">*</span></label>
                                    <select class="form-select" id="editDepartment" required>
                                        <option value="" disabled>Select a department</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Design">Design</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Sales">Sales</option>
                                        <option value="HR">Human Resources</option>
                                        <option value="Finance">Finance</option>
                                        <option value="Operations">Operations</option>
                                        <option value="IT">Information Technology</option>
                                        <option value="unemployed">Unemployed</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveEmployeeChanges()">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Times Modal -->
        <div class="modal fade" id="viewTimesModal" tabindex="-1" aria-labelledby="viewTimesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewTimesModalLabel">Time Records</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Clock In</th>
                                        <th>Tea Clock Out</th>
                                        <th>Tea Clock In</th>
                                        <th>Lunch Clock Out</th>
                                        <th>Lunch Clock In</th>
                                        <th>Clock Out</th>
                                        <th>Total Hours</th>
                                    </tr>
                                </thead>
                                <tbody id="timeRecordsBody">
                                    <!-- Time records will be populated here by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="fa-solid fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                            <p>Are you sure you want to delete <strong id="deleteEmployeeName"></strong> (<span id="deleteEmployeeId"></span>)?</p>
                            <p class="text-danger"><small>This action cannot be undone.</small></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sample employee data
        let employees = [
            {
                id: 1,
                name: 'John Doe',
                employeeId: 'EMP001',
                classificationId: 'CL001',
                firstName: 'John',
                lastName: 'Doe',
                contactNo: '+1 (555) 123-4567',
                email: 'john.doe@company.com',
                address: '123 Main St, New York, NY',
                idNumber: '123456789',
                userType: 'Employee',
                dateHired: '2023-01-15',
                supervisorName: 'Sarah Wilson',
                leaveBalance: 20,
                username: 'johndoe',
                password: 'password123',
                roles: 'Developer',
                department: 'Engineering',
                status: 'on-site'
            },
            {
                id: 2,
                name: 'Jane Smith',
                employeeId: 'EMP002',
                classificationId: 'CL002',
                firstName: 'Jane',
                lastName: 'Smith',
                contactNo: '+1 (555) 234-5678',
                email: 'jane.smith@company.com',
                address: '456 Oak Ave, Los Angeles, CA',
                idNumber: '234567890',
                userType: 'Employee',
                dateHired: '2023-02-20',
                supervisorName: 'Mike Johnson',
                leaveBalance: 18,
                username: 'janesmith',
                password: 'password123',
                roles: 'Designer',
                department: 'Design',
                status: 'remote'
            },
            {
                id: 3,
                name: 'Mike Jordan',
                employeeId: 'EMP003',
                classificationId: 'CL003',
                firstName: 'Mike',
                lastName: 'Jordan',
                contactNo: '+1 (555) 345-6789',
                email: 'mike.jordan@company.com',
                address: '789 Pine Rd, Chicago, IL',
                idNumber: '345678901',
                userType: 'Admin',
                dateHired: '2022-11-10',
                supervisorName: '',
                leaveBalance: 22,
                username: 'mikejordan',
                password: 'password123',
                roles: 'Manager',
                department: 'Operations',
                status: 'on-site'
            }
        ];

        // Sample time records
        let employeeTimes = [
            {
                id: 1,
                date: '2025-08-01',
                work_clockin: '09:00 AM',
                work_clockout: '05:00 PM',
                tea_clockin: '10:30 AM',
                tea_clockout: '10:45 AM',
                lunch_clockin: '01:00 PM',
                lunch_clockout: '02:00 PM',
                totalHours: 8,
            },
            {
                id: 2,
                date: '2025-08-02',
                work_clockin: '09:15 AM',
                work_clockout: '05:15 PM',
                tea_clockin: '10:30 AM',
                tea_clockout: '10:45 AM',
                lunch_clockin: '01:00 PM',
                lunch_clockout: '02:00 PM',
                totalHours: 8,
            },
            {
                id: 3,
                date: '2025-08-03',
                work_clockin: '08:45 AM',
                work_clockout: '04:45 PM',
                tea_clockin: '10:30 AM',
                tea_clockout: '10:45 AM',
                lunch_clockin: '01:00 PM',
                lunch_clockout: '02:00 PM',
                totalHours: 8,
            },
        ];

        // Global variables
        let selectedEmployeeId = null;
        let selectedEmployee = null;

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            renderEmployees();
            
            // Add event listener for search
            document.getElementById('searchQuery').addEventListener('input', function() {
                renderEmployees();
            });
            
            // Set default date for dateHired field
            document.getElementById('dateHired').valueAsDate = new Date();
        });

        // Render employees based on search query
        function renderEmployees() {
            const searchQuery = document.getElementById('searchQuery').value.toLowerCase();
            const filteredEmployees = employees.filter(employee => {
                return employee.name.toLowerCase().includes(searchQuery) ||
                       employee.employeeId.toLowerCase().includes(searchQuery) ||
                       employee.roles.toLowerCase().includes(searchQuery) ||
                       employee.department.toLowerCase().includes(searchQuery);
            });
            
            renderDesktopView(filteredEmployees);
            renderMobileView(filteredEmployees);
        }

        // Render desktop table view
        function renderDesktopView(employeesList) {
            const tableBody = document.getElementById('employeesTableBody');
            tableBody.innerHTML = '';
            
            if (employeesList.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No employees found</td></tr>';
                return;
            }
            
            employeesList.forEach(employee => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${employee.name}</td>
                    <td>${employee.employeeId}</td>
                    <td>${employee.department}</td>
                    <td>${employee.roles}</td>
                    <td>${employee.status}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                ⁝
                            </button>
                            <ul class="dropdown-menu">
                                <li><button class="dropdown-item" onclick="openEditModal(${employee.id})">Edit</button></li>
                                <li><button class="dropdown-item" onclick="openViewTimesModal(${employee.id})">View Times</button></li>
                                <li><button class="dropdown-item text-danger" onclick="openDeleteModal(${employee.id})">Delete</button></li>
                            </ul>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Render mobile card view
        function renderMobileView(employeesList) {
            const container = document.getElementById('mobileEmployeesContainer');
            container.innerHTML = '';
            
            if (employeesList.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-4">No employees found</div>';
                return;
            }
            
            employeesList.forEach(employee => {
                const card = document.createElement('div');
                card.className = 'employee-card';
                card.innerHTML = `
                    <div class="card-header">
                        <div class="employee-info">
                            <h6 class="employee-name">${employee.name}</h6>
                            <span class="employee-id">${employee.employeeId}</span>
                        </div>
                        <div class="status-indicator">
                            <i class="fa-solid fa-circle-dot" style="color: ${employee.status === 'on-site' ? '#00ffb3' : '#6c757d'}"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="employee-details">
                            <div class="detail-item">
                                <span class="label">Role:</span>
                                <span class="value">${employee.roles}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Department:</span>
                                <span class="value">${employee.department}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Status:</span>
                                <span class="value">${employee.status}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(${employee.id})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="openViewTimesModal(${employee.id})">
                                <i class="fa-solid fa-clock"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="openDeleteModal(${employee.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // Toggle password visibility
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleButton = passwordField.nextElementSibling;
            const icon = toggleButton.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                passwordField.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }

        // Add new employee
        function addNewEmployee() {
            const requiredFields = [
                'employeeId', 'classificationId', 'firstName', 'lastName', 
                'contactNo', 'email', 'address', 'idNumber', 'dateHired',
                'leaveBalance', 'username', 'password', 'roles', 'department'
            ];
            
            // Check if all required fields are filled
            for (let field of requiredFields) {
                const fieldElement = document.getElementById(field);
                if (!fieldElement.value) {
                    alert(`Please fill in the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field.`);
                    return;
                }
            }
            
            // Check if employee ID already exists
            const employeeId = document.getElementById('employeeId').value;
            if (employees.some(emp => emp.employeeId === employeeId)) {
                alert('Employee ID already exists. Please use a unique ID.');
                return;
            }
            
            // Check if username already exists
            const username = document.getElementById('username').value;
            if (employees.some(emp => emp.username === username)) {
                alert('Username already exists. Please choose a different username.');
                return;
            }
            
            // Get user type
            const userTypeRadios = document.getElementsByName('userType');
            let userType = 'Employee';
            for (let radio of userTypeRadios) {
                if (radio.checked) {
                    userType = radio.value;
                    break;
                }
            }
            
            // Create new employee object
            const newEmployee = {
                id: employees.length > 0 ? Math.max(...employees.map(e => e.id)) + 1 : 1,
                name: `${document.getElementById('firstName').value} ${document.getElementById('lastName').value}`,
                employeeId: employeeId,
                classificationId: document.getElementById('classificationId').value,
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                contactNo: document.getElementById('contactNo').value,
                email: document.getElementById('email').value,
                address: document.getElementById('address').value,
                idNumber: document.getElementById('idNumber').value,
                userType: userType,
                dateHired: document.getElementById('dateHired').value,
                supervisorName: document.getElementById('supervisorName').value,
                leaveBalance: parseInt(document.getElementById('leaveBalance').value),
                username: username,
                password: document.getElementById('password').value,
                roles: document.getElementById('roles').value,
                department: document.getElementById('department').value,
                status: 'on-site'
            };
            
            // Add to employees array
            employees.push(newEmployee);
            
            // Close modal and reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal'));
            modal.hide();
            document.getElementById('addEmployeeForm').reset();
            
            // Re-render employees
            renderEmployees();
            
            console.log(`Employee ${newEmployee.name} added successfully.`);
        }

        // Open edit modal
        function openEditModal(employeeId) {
            selectedEmployeeId = employeeId;
            selectedEmployee = employees.find(emp => emp.id === employeeId);
            
            if (!selectedEmployee) return;
            
            // Populate form fields
            document.getElementById('editEmployeeId').value = selectedEmployee.employeeId;
            document.getElementById('editClassificationId').value = selectedEmployee.classificationId;
            document.getElementById('editFirstName').value = selectedEmployee.firstName;
            document.getElementById('editLastName').value = selectedEmployee.lastName;
            document.getElementById('editContactNo').value = selectedEmployee.contactNo;
            document.getElementById('editEmail').value = selectedEmployee.email;
            document.getElementById('editAddress').value = selectedEmployee.address;
            document.getElementById('editIdNumber').value = selectedEmployee.idNumber;
            
            // Set user type
            const userTypeRadios = document.getElementsByName('editUserType');
            for (let radio of userTypeRadios) {
                radio.checked = (radio.value === selectedEmployee.userType);
            }
            
            document.getElementById('editDateHired').value = selectedEmployee.dateHired;
            document.getElementById('editSupervisorName').value = selectedEmployee.supervisorName;
            document.getElementById('editLeaveBalance').value = selectedEmployee.leaveBalance;
            document.getElementById('editUsername').value = selectedEmployee.username;
            document.getElementById('editPassword').value = '';
            document.getElementById('editRoles').value = selectedEmployee.roles;
            document.getElementById('editDepartment').value = selectedEmployee.department;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
            modal.show();
        }

        // Save employee changes
        function saveEmployeeChanges() {
            if (!selectedEmployee) return;
            
            const requiredFields = [
                'editEmployeeId', 'editClassificationId', 'editFirstName', 'editLastName', 
                'editContactNo', 'editEmail', 'editAddress', 'editIdNumber', 'editDateHired',
                'editLeaveBalance', 'editUsername', 'editRoles', 'editDepartment'
            ];
            
            // Check if all required fields are filled
            for (let field of requiredFields) {
                const fieldElement = document.getElementById(field);
                if (!fieldElement.value) {
                    alert(`Please fill in the ${field.replace('edit', '').replace(/([A-Z])/g, ' $1').toLowerCase()} field.`);
                    return;
                }
            }
            
            // Check if employee ID already exists (excluding current employee)
            const employeeId = document.getElementById('editEmployeeId').value;
            if (employees.some(emp => emp.id !== selectedEmployeeId && emp.employeeId === employeeId)) {
                alert('Employee ID already exists. Please use a unique ID.');
                return;
            }
            
            // Check if username already exists (excluding current employee)
            const username = document.getElementById('editUsername').value;
            if (employees.some(emp => emp.id !== selectedEmployeeId && emp.username === username)) {
                alert('Username already exists. Please choose a different username.');
                return;
            }
            
            // Get user type
            const userTypeRadios = document.getElementsByName('editUserType');
            let userType = 'Employee';
            for (let radio of userTypeRadios) {
                if (radio.checked) {
                    userType = radio.value;
                    break;
                }
            }
            
            // Update employee object
            selectedEmployee.employeeId = employeeId;
            selectedEmployee.classificationId = document.getElementById('editClassificationId').value;
            selectedEmployee.firstName = document.getElementById('editFirstName').value;
            selectedEmployee.lastName = document.getElementById('editLastName').value;
            selectedEmployee.name = `${selectedEmployee.firstName} ${selectedEmployee.lastName}`;
            selectedEmployee.contactNo = document.getElementById('editContactNo').value;
            selectedEmployee.email = document.getElementById('editEmail').value;
            selectedEmployee.address = document.getElementById('editAddress').value;
            selectedEmployee.idNumber = document.getElementById('editIdNumber').value;
            selectedEmployee.userType = userType;
            selectedEmployee.dateHired = document.getElementById('editDateHired').value;
            selectedEmployee.supervisorName = document.getElementById('editSupervisorName').value;
            selectedEmployee.leaveBalance = parseInt(document.getElementById('editLeaveBalance').value);
            selectedEmployee.username = username;
            
            // Update password only if provided
            const password = document.getElementById('editPassword').value;
            if (password) {
                selectedEmployee.password = password;
            }
            
            selectedEmployee.roles = document.getElementById('editRoles').value;
            selectedEmployee.department = document.getElementById('editDepartment').value;
            
            // Update employees array
            const index = employees.findIndex(emp => emp.id === selectedEmployeeId);
            if (index !== -1) {
                employees[index] = selectedEmployee;
            }
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editEmployeeModal'));
            modal.hide();
            
            // Re-render employees
            renderEmployees();
            
            console.log(`Employee ${selectedEmployee.name} updated successfully.`);
        }

        // Open view times modal
        function openViewTimesModal(employeeId) {
            selectedEmployeeId = employeeId;
            selectedEmployee = employees.find(emp => emp.id === employeeId);
            
            if (!selectedEmployee) return;
            
            // Update modal title
            document.getElementById('viewTimesModalLabel').textContent = `Time Records for ${selectedEmployee.name}`;
            
            // Populate time records
            const timeRecordsBody = document.getElementById('timeRecordsBody');
            timeRecordsBody.innerHTML = '';
            
            // For demo purposes, we'll use the sample time records
            // In a real application, you would fetch time records for the specific employee
            employeeTimes.forEach(timeRecord => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${formatDate(timeRecord.date)}</td>
                    <td>${timeRecord.work_clockin}</td>
                    <td>${timeRecord.tea_clockout}</td>
                    <td>${timeRecord.tea_clockin}</td>
                    <td>${timeRecord.lunch_clockout}</td>
                    <td>${timeRecord.lunch_clockin}</td>
                    <td>${timeRecord.work_clockout}</td>
                    <td>${timeRecord.totalHours} hours</td>
                `;
                timeRecordsBody.appendChild(row);
            });
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('viewTimesModal'));
            modal.show();
        }

        // Format date
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
            });
        }

        // Open delete confirmation modal
        function openDeleteModal(employeeId) {
            selectedEmployeeId = employeeId;
            selectedEmployee = employees.find(emp => emp.id === employeeId);
            
            if (!selectedEmployee) return;
            
            // Update modal content
            document.getElementById('deleteEmployeeName').textContent = selectedEmployee.name;
            document.getElementById('deleteEmployeeId').textContent = selectedEmployee.employeeId;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            modal.show();
        }

        // Confirm delete
        function confirmDelete() {
            if (!selectedEmployee) return;
            
            // Remove employee from array
            employees = employees.filter(emp => emp.id !== selectedEmployeeId);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
            modal.hide();
            
            // Re-render employees
            renderEmployees();
            
            console.log(`Employee ${selectedEmployee.name} has been deleted.`);
        }
    </script>
</body>
</html>