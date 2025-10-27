<template>
  <div>
  <!-- add employee search bar and history -->
  
    <div class="search-add-container mb-4">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-md-8 col-lg-9">
          <div class="search-container">
            <input 
              type="text" 
              class="form-control search-input" 
              placeholder="Search employees..." 
              v-model="searchQuery"
            >
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
          <button class="btn btn-primary w-100 add-employee-btn" @click="openAddEmployeeModal">
            <i class="fa-solid fa-plus me-2"></i>
            Add Employee
          </button>
        </div>
      </div>
    </div>
    
    <!-- Mobile Card View -->
    <div class="d-md-none mobile-employees-container">
      <div class="employee-card" v-for="employee in filteredList" :key="employee.id">
        <div class="card-header">
          <div class="employee-info">
            <h6 class="employee-name">{{ employee.name }}</h6>
            <span class="employee-id">{{ employee.employeeId }}</span>
          </div>
          <div class="status-indicator">
            <i class="fa-solid fa-circle-dot" style="color: #00ffb3;"></i>
          </div>
        </div>
        <div class="card-body">
          <div class="employee-details">
            <div class="detail-item">
              <span class="label">Role:</span>
              <span class="value">{{ employee.roles }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Department:</span>
              <span class="value">{{ employee.department }}</span>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="action-buttons">
            <button class="btn btn-sm btn-outline-primary" @click="openEditModal(employee)">
              <i class="fa-solid fa-pen"></i>
            </button>
            <button class="btn btn-sm btn-outline-info" @click="openViewTimesModal(employee)">
              <i class="fa-solid fa-clock"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger" @click="openDeleteModal(employee)">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
      <div v-if="filteredList.length === 0" class="text-center text-muted py-4">
        No employees found
      </div>
    </div>

    <!-- Desktop Table View -->
    <div class="d-none d-md-block">
      <div class="table-responsive">
        <table class="table table-striped employee-table">
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Employee ID</th>
              <th scope="col">Roles</th>
              <th scope="col">Department</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="employee in filteredList" :key="employee.id">
              <td>{{ employee.name }}</td>
              <td>{{ employee.employeeId }}</td>
              <td>{{ employee.roles }}</td>
              <td>{{ employee.department }}</td>
              <td>{{ employee.status }}</td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    ⁝
                  </button>
                  <ul class="dropdown-menu">
                    <li><button class="dropdown-item" @click="openEditModal(employee)">Edit</button></li>
                    <li><button class="dropdown-item" @click="openViewTimesModal(employee)">View Times</button></li>
                    <li><button class="dropdown-item text-danger" @click="openDeleteModal(employee)">Delete</button></li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr v-if="filteredList.length === 0">
              <td colspan="6" class="text-center text-muted">No employees found</td>
            </tr>
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
            <form @submit.prevent="addNewEmployee">
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
                    v-model="newEmployee.employeeId"
                    required
                    placeholder="EMP001"
                  >
                </div>
                
                <div class="col-md-6">
                  <label for="classificationId" class="form-label">Classification ID <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="classificationId" 
                    v-model="newEmployee.classificationId"
                    required
                    placeholder="CL001"
                  >
                </div>

                <div class="col-md-6">
                  <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="firstName" 
                    v-model="newEmployee.firstName"
                    required
                    placeholder="John"
                  >
                </div>

                <div class="col-md-6">
                  <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="lastName" 
                    v-model="newEmployee.lastName"
                    required
                    placeholder="Doe"
                  >
                </div>

                <div class="col-md-6">
                  <label for="contactNo" class="form-label">Contact Number <span class="text-danger">*</span></label>
                  <input 
                    type="tel" 
                    class="form-control" 
                    id="contactNo" 
                    v-model="newEmployee.contactNo"
                    required
                    placeholder="+1 (555) 123-4567"
                  >
                </div>

                <div class="col-md-6">
                  <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                  <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    v-model="newEmployee.email"
                    required
                    placeholder="john.doe@company.com"
                  >
                </div>

                <div class="col-12">
                  <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                  <textarea 
                    class="form-control" 
                    id="address" 
                    v-model="newEmployee.address"
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
                    v-model="newEmployee.idNumber"
                    required
                    placeholder="123456789"
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
                    v-model="newEmployee.dateHired"
                    required
                  >
                </div>

                <div class="col-md-6">
                  <label for="supervisorName" class="form-label">Supervisor Name</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="supervisorName" 
                    v-model="newEmployee.supervisorName"
                    placeholder="Sarah Wilson"
                  >
                </div>

                <div class="col-md-6">
                  <label for="leaveBalance" class="form-label">Leave Balance <span class="text-danger">*</span></label>
                  <input 
                    type="number" 
                    class="form-control" 
                    id="leaveBalance" 
                    v-model="newEmployee.leaveBalance"
                    required
                    min="0"
                    placeholder="20"
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
                        v-model="newEmployee.userType"
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
                        v-model="newEmployee.userType"
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
                    v-model="newEmployee.username"
                    required
                    placeholder="johndoe"
                  >
                </div>

                <div class="col-md-6">
                  <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                  <div class="password-input-group">
                    <input 
                      :type="showPassword ? 'text' : 'password'" 
                      class="form-control" 
                      id="password" 
                      v-model="newEmployee.password"
                      required
                      placeholder="Enter password"
                    >
                    <button 
                      type="button" 
                      class="btn btn-outline-secondary password-toggle"
                      @click="showPassword = !showPassword"
                    >
                      <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                    </button>
                  </div>
                </div>

                <!-- Roles and Department -->
                <div class="col-12 mt-4">
                  <h6 class="section-title">Roles & Department</h6>
                </div>

                <div class="col-md-6">
                  <label for="roles" class="form-label">Roles <span class="text-danger">*</span></label>
                  <select class="form-select" id="roles" v-model="newEmployee.roles" required>
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
                  <select class="form-select" id="department" v-model="newEmployee.department" required>
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

                <!-- Status -->
                <div class="col-12 mt-4">
                  <h6 class="section-title">Status</h6>
                </div>

                <div class="col-12">
                  <div class="form-check form-check-inline">
                    <input 
                      class="form-check-input" 
                      type="radio" 
                      id="statusActive" 
                      value="on-site" 
                      v-model="newEmployee.status"
                      checked
                    >
                    <label class="form-check-label" for="statusActive">
                      <i class="fa-solid fa-circle-dot text-success me-1"></i> On-site
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input 
                      class="form-check-input" 
                      type="radio" 
                      id="statusInactive" 
                      value="remote" 
                      v-model="newEmployee.status"
                    >
                    <label class="form-check-label" for="statusInactive">
                      <i class="fa-solid fa-circle-dot text-secondary me-1"></i> Remote
                    </label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="addNewEmployee">Add Employee</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form v-if="selectedEmployee">
              <div class="mb-3">
                <label for="employeeName" class="form-label">Name</label>
                <input type="text" class="form-control" id="employeeName" v-model="selectedEmployee.name">
              </div>
              <div class="mb-3">
                <label for="employeeId" class="form-label">Employee ID</label>
                <input type="text" class="form-control" id="employeeId" v-model="selectedEmployee.employeeId">
              </div>
              <div class="mb-3">
                <label for="employeeRoles" class="form-label">Roles</label>
                <input type="text" class="form-control" id="employeeRoles" v-model="selectedEmployee.roles">
              </div>
              <div class="mb-3">
                <label for="employeeDepartment" class="form-label">Department</label>
                <input type="text" class="form-control" id="employeeDepartment" v-model="selectedEmployee.department">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveEmployeeChanges">Save Changes</button>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="viewTimesModal" tabindex="-1" aria-labelledby="viewTimesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewTimesModalLabel">Time Records for {{ selectedEmployee?.name }}</h5>
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
                <tbody>
                  <tr v-for="time in employeeTimes" :key="time.id">
                    <td>{{ formatDate(time.date) }}</td>
                    <td>{{ time.clockIn }}</td>
                    <td>{{ time.teaclockout }}</td>
                    <td>{{ time.teaclockin }}</td>
                    <td>{{ time.lunchclockout }}</td>
                    <td>{{ time.lunchclockin }}</td>
                    <td>{{ time.clockOut }}</td>
                    <td>{{ time.totalHours }} hours</td>
                  </tr>
                  <tr v-if="employeeTimes.length === 0">
                    <td colspan="8" class="text-center text-muted">No time records found</td>
                  </tr>
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
              <p>Are you sure you want to delete <strong>{{ selectedEmployee?.name }}</strong> ({{ selectedEmployee?.employeeId }})?</p>
              <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" @click="confirmDelete">Yes, Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchQuery: '',
      dateQuery: '',
      selectedEmployee: null,
      showPassword: false,
      newEmployee: {
        employeeId: '',
        classificationId: '',
        firstName: '',
        lastName: '',
        contactNo: '',
        email: '',
        address: '',
        idNumber: '',
        userType: 'Employee',
        dateHired: '',
        supervisorName: '',
        leaveBalance: 20,
        username: '',
        password: '',
        roles: '',
        department: '',
        status: 'on-site'
      },
      employees: [
        {
          id: 1,
          name: 'John Doe',
          employeeId: 'EMP001',
          roles: 'unemployed',
          department: 'unemployed',
          status: 'on-site',
        },
        {
          id: 2,
          name: 'Jane SMite',
          employeeId: 'EMP002',
          roles: 'unemployed',
          department: 'unemployed',
          status: 'on-site',
        },
        {
          id: 3,
          name: 'Mike Jordan',
          employeeId: 'EMP003',
          roles: 'unemployed',
          department: 'unemployed',
          status: 'on-site',
        },
        {
          id: 4,
          name: 'Sarah Adams',
          employeeId: 'EMP004',
          roles: 'unemployed',
          department: 'unemployed',
          status: 'on-site',
        },
        {
          id: 5,
          name: 'David johnson',
          employeeId: 'EMP005',
          roles: 'unemployed',
          department: 'unemployed',
          status: 'on-site',
        },
        {
          id: 6,
          name: 'Emily Moe lester',
          employeeId: 'EMP006',
          roles: 'unemployed',
          department: 'unemployed',
          status: 'on-site',
        }
      ],
      employeeTimes: [
        {
          id: 1,
          date: '2025-08-01',
          clockIn: '09:00 AM',
          clockOut: '05:00 PM',
          teaclockin: '10:30 AM',
          teaclockout: '10:45 AM',
          lunchclockin: '01:00 PM',
          lunchclockout: '02:00 PM',
          totalHours: 8,
        },
        {
          id: 2,
          date: '2025-08-02',
          clockIn: '09:15 AM',
          clockOut: '05:15 PM',
          teaclockin: '10:30 AM',
          teaclockout: '10:45 AM',
          lunchclockin: '01:00 PM',
          lunchclockout: '02:00 PM',
          totalHours: 8,
        },
        {
          id: 3,
          date: '2025-08-03',
          clockIn: '08:45 AM',
          clockOut: '04:45 PM',
          teaclockin: '10:30 AM',
          teaclockout: '10:45 AM',
          lunchclockin: '01:00 PM',
          lunchclockout: '02:00 PM',
          totalHours: 8,
        },  
      ]
    }
  },
  computed: {
    filteredList() {
      return this.employees.filter(item => {
        const matchesText = item.name?.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                           item.surname?.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                           item.employeeId?.toLowerCase().includes(this.searchQuery.toLowerCase());
      
        return matchesText;
      });
    }
  },
  methods: {
    clearFilters() {
      this.dateQuery = '';
      this.searchQuery = '';
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
    openAddEmployeeModal() {
      // Reset the form with default values
      this.newEmployee = {
        employeeId: '',
        classificationId: '',
        firstName: '',
        lastName: '',
        contactNo: '',
        email: '',
        address: '',
        idNumber: '',
        userType: 'Employee',
        dateHired: new Date().toISOString().split('T')[0], // Today's date as default
        supervisorName: '',
        leaveBalance: 20,
        username: '',
        password: '',
        roles: '',
        department: '',
        status: 'on-site'
      };
      this.showPassword = false;
      const modal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
      modal.show();
    },
    addNewEmployee() {
      // Basic validation
      const requiredFields = [
        'employeeId', 'classificationId', 'firstName', 'lastName', 
        'contactNo', 'email', 'address', 'idNumber', 'dateHired',
        'leaveBalance', 'username', 'password', 'roles', 'department'
      ];
      
      for (let field of requiredFields) {
        if (!this.newEmployee[field]) {
          alert(`Please fill in the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field.`);
          return;
        }
      }

      // Check if employee ID already exists
      if (this.employees.some(emp => emp.employeeId === this.newEmployee.employeeId)) {
        alert('Employee ID already exists. Please use a unique ID.');
        return;
      }

      // Check if username already exists
      if (this.employees.some(emp => emp.username === this.newEmployee.username)) {
        alert('Username already exists. Please choose a different username.');
        return;
      }

      // Create new employee object
      const newEmployee = {
        id: Math.max(...this.employees.map(emp => emp.id)) + 1,
        name: `${this.newEmployee.firstName} ${this.newEmployee.lastName}`,
        employeeId: this.newEmployee.employeeId,
        classificationId: this.newEmployee.classificationId,
        firstName: this.newEmployee.firstName,
        lastName: this.newEmployee.lastName,
        contactNo: this.newEmployee.contactNo,
        email: this.newEmployee.email,
        address: this.newEmployee.address,
        idNumber: this.newEmployee.idNumber,
        userType: this.newEmployee.userType,
        dateHired: this.newEmployee.dateHired,
        supervisorName: this.newEmployee.supervisorName,
        leaveBalance: this.newEmployee.leaveBalance,
        username: this.newEmployee.username,
        password: this.newEmployee.password,
        roles: this.newEmployee.roles,
        department: this.newEmployee.department,
        status: this.newEmployee.status
      };

      // Add to employees array
      this.employees.push(newEmployee);

      // Close modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal'));
      modal.hide();

      // Optional: Show success message
      console.log(`Employee ${newEmployee.name} added successfully.`);
    },
    openEditModal(employee) {
      this.selectedEmployee = {...employee};
      const modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
      modal.show();
    },
    openViewTimesModal(employee) {
      this.selectedEmployee = employee;
      const modal = new bootstrap.Modal(document.getElementById('viewTimesModal'));
      modal.show();
    },
    openDeleteModal(employee) {
      this.selectedEmployee = employee;
      const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
      modal.show();
    },
    confirmDelete() {
      if (this.selectedEmployee) {
        this.employees = this.employees.filter(emp => emp.id !== this.selectedEmployee.id);
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
        modal.hide();
        console.log(`Employee ${this.selectedEmployee.name} has been deleted.`);
      }
    },
    saveEmployeeChanges() {
      const index = this.employees.findIndex(emp => emp.id === this.selectedEmployee.id);
      if (index !== -1) {
        this.employees[index] = {...this.selectedEmployee};
      }
      const modal = bootstrap.Modal.getInstance(document.getElementById('editEmployeeModal'));
      modal.hide();
    },
  },
}
</script>

<style scoped>
/* Search and Add Container */
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

/* Search Container */
.search-container {
  width: 100%;
  padding: 15px;
  border: 1px solid #00C0AA;
  border-radius: 8px;
  background-color: #f8f9fa;
}

@media (min-width: 576px) {
  .search-container {
    padding: 20px;
  }
}

.search-input {
  width: 100%;
  border: 1px solid #00C0AA;
}

.search-input:focus {
  border-color: #00C0AA;
  box-shadow: 0 0 0 0.2rem rgba(0, 192, 170, 0.25);
}

/* Add Employee Button */
.add-employee-btn {
  height: 100%;
  min-height: 60px;
  padding: 12px 20px;
  font-weight: 600;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;
}

@media (max-width: 767.98px) {
  .add-employee-btn {
    min-height: 50px;
    margin-top: 10px;
  }
}

/* Modal Styles */
.modal-xl {
  max-width: 1140px;
}

.section-title {
  color: #00C0AA;
  font-weight: 600;
  border-bottom: 2px solid #00C0AA;
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
  border: 1px solid #00C0AA;
  border-radius: 6px;
}

.form-control:focus, .form-select:focus {
  border-color: #00C0AA;
  box-shadow: 0 0 0 0.2rem rgba(0, 192, 170, 0.25);
}

.form-check-input:checked {
  background-color: #00C0AA;
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
  border-left: 1px solid #00C0AA;
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
  border: 1px solid #00C0AA;
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

/* Desktop Table View */
.table-responsive {
  overflow-x: auto;
}

.employee-table {
  margin: 0 auto;
  width: 95%;
  border: 1px solid #00C0AA;
}

@media (min-width: 1200px) {
  .employee-table {
    width: 90%;
  }
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
  border: 1px solid #00C0AA;
}

.modal-header {
  border-bottom: 1px solid #00C0AA;
  padding: 1rem 1rem;
}

.modal-body {
  padding: 1.5rem;
  max-height: 70vh;
  overflow-y: auto;
}

.modal-footer {
  border-top: 1px solid #00C0AA;
  padding: 1rem;
}

/* Button Styles */
.btn-primary {
  background-color: #00C0AA;
  border-color: #00C0AA;
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

/* Responsive adjustments for the form */
@media (max-width: 768px) {
  .modal-body {
    padding: 1rem;
    max-height: 60vh;
  }
  
  .section-title {
    font-size: 1rem;
  }
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

/* Text and spacing */
.text-muted {
  color: #6c757d !important;
}
</style>