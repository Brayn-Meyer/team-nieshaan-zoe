  <template>
  <div>

    <div class="search-add-container mb-4">
      <div class="row g-3 align-items-center">
        <div class="col-auto">
          <div class="d-flex gap-2">
            <button class="btn btn-primary small-btn" @click="HistoryView">
              <i class="fa-solid fa-clock-rotate-left"></i>
              History
            </button>
            <button class="btn btn-primary small-btn" @click="openMapModal">
              <i class="fa-solid fa-map-location-dot"></i>
              Map
            </button>
          </div>
        </div>
        
        <div class="col">
          <div class="search-container">
            <input 
              type="text" 
              class="form-control search-input" 
              placeholder="Search employees..." 
              v-model="searchQuery"
            >
          </div>
        </div>
        
        <div class="col-auto">
          <button class="btn btn-primary small-btn" @click="openAddEmployeeModal">
            <i class="fa-solid fa-plus me-1"></i>
            Add Employee
          </button>
        </div>
      </div>
    </div>
    
    <div class="d-md-none mobile-employees-container">
      <div class="employee-card" v-for="employee in filteredList" :key="employee.id">
        <div class="card-header">
          <div class="employee-info">
            <h6 class="employee-name">{{ employee.name }}</h6>
            <span class="employee-id">{{ employee.employeeId }}</span>
          </div>
          <div class="status-indicator">
            <i class="fa-solid fa-circle-dot" :style="{ color: employee.status === 'on-site' ? '#00ffb3' : '#6c757d' }"></i>
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
            <div class="detail-item">
              <span class="label">Status:</span>
              <span class="value">{{ employee.status }}</span>
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
    <div class="d-none d-md-block">
      <div class="table-responsive">
        <table class="table table-striped employee-table">
          <thead>
            <tr>
              <th scope="col">Employee ID</th>
              <th scope="col">Name</th>
              <th scope="col">Roles</th>
              <th scope="col">Department</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="employee in filteredList" :key="employee.id">
              <td>{{ employee.employeeId }}</td>
              <td>{{ employee.name }}</td>
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
                  <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="firstName" 
                    v-model="newEmployee.firstName"
                    required
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
                  >
                </div>
                
                <!-- <div class="col-md-6">
                  <label for="classificationId" class="form-label">Classification ID <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="classificationId" 
                    v-model="newEmployee.classificationId"
                    required
                  >
                </div> -->

                <div class="col-md-6">
                  <label for="contactNo" class="form-label">Contact Number <span class="text-danger">*</span></label>
                  <input 
                    type="tel" 
                    class="form-control" 
                    id="contactNo" 
                    v-model="newEmployee.contactNo"
                    required
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
                  >
                </div>

                <div class="col-md-6">
                  <label for="idNumber" class="form-label">ID Number <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="idNumber" 
                    v-model="newEmployee.idNumber"
                    required
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
                    <option value="" disabled>Select a role</option>
                    <option v-for="role in roles" :key="role.role" :value="role.role">
                      {{ role.role }}
                    </option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                  <select class="form-select" id="department" v-model="newEmployee.department" required>
                    <option value="" disabled selected>Select a department</option>
                    <option v-for="department in departments" 
                            :key="department" 
                            :value="department">
                      {{ department }}
                    </option>
                  </select>
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
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form v-if="selectedEmployee">
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
                    v-model="selectedEmployee.employeeId"
                    required
                  >
                </div>
<!--                 
                <div class="col-md-6">
                  <label for="editClassificationId" class="form-label">Classification ID <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="editClassificationId" 
                    v-model="selectedEmployee.classificationId"
                    required
                  >
                </div> -->

                <div class="col-md-6">
                  <label for="editFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="editFirstName" 
                    v-model="selectedEmployee.firstName"
                    required
                  >
                </div>

                <div class="col-md-6">
                  <label for="editLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="editLastName" 
                    v-model="selectedEmployee.lastName"
                    required
                  >
                </div>

                <div class="col-md-6">
                  <label for="editContactNo" class="form-label">Contact Number <span class="text-danger">*</span></label>
                  <input 
                    type="tel" 
                    class="form-control" 
                    id="editContactNo" 
                    v-model="selectedEmployee.contactNo"
                    required
                  >
                </div>

                <div class="col-md-6">
                  <label for="editEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                  <input 
                    type="email" 
                    class="form-control" 
                    id="editEmail" 
                    v-model="selectedEmployee.email"
                    required
                  >
                </div>

                <div class="col-12">
                  <label for="editAddress" class="form-label">Address <span class="text-danger">*</span></label>
                  <textarea 
                    class="form-control" 
                    id="editAddress" 
                    v-model="selectedEmployee.address"
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
                    v-model="selectedEmployee.idNumber"
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
                    v-model="selectedEmployee.dateHired"
                    required
                  >
                </div>

                <div class="col-md-6">
                  <label for="editSupervisorName" class="form-label">Supervisor Name</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="editSupervisorName" 
                    v-model="selectedEmployee.supervisorName"
                  >
                </div>

                <div class="col-md-6">
                  <label for="editLeaveBalance" class="form-label">Leave Balance <span class="text-danger">*</span></label>
                  <input 
                    type="number" 
                    class="form-control" 
                    id="editLeaveBalance" 
                    v-model="selectedEmployee.leaveBalance"
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
                        v-model="selectedEmployee.userType"
                      >
                      <label class="form-check-label" for="editUserTypeEmployee">Employee</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input 
                        class="form-check-input" 
                        type="radio" 
                        id="editUserTypeAdmin" 
                        value="Admin" 
                        v-model="selectedEmployee.userType"
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
                    v-model="selectedEmployee.username"
                    required
                  >
                </div>

                <div class="col-md-6">
                  <label for="editPassword" class="form-label">Password</label>
                  <div class="password-input-group">
                    <input 
                      :type="showEditPassword ? 'text' : 'password'" 
                      class="form-control" 
                      id="editPassword" 
                      v-model="selectedEmployee.password"
                      placeholder="Leave blank to keep current password"
                    >
                    <button 
                      type="button" 
                      class="btn btn-outline-secondary password-toggle"
                      @click="showEditPassword = !showEditPassword"
                    >
                      <i :class="showEditPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
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
                  <select class="form-select" id="editRoles" v-model="selectedEmployee.roles" required>
                    <option value="" disabled>Select a role</option>
                    <option v-for="role in roles" :key="role.role" :value="role.role">
                      {{ role.role }}
                    </option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="editDepartment" class="form-label">Department <span class="text-danger">*</span></label>
                  <select class="form-select" id="editDepartment" v-model="selectedEmployee.department" required>
                    <option value="" disabled>Select a department</option>
                    <option v-for="department in departments" 
                            :key="department" 
                            :value="department">
                      {{ department }}
                    </option>
                  </select>
                </div>
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

    <!-- View Times Modal -->
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
import HistoryView from '@/views/HistoryView.vue'
import axios from 'axios';
import API_URL from '../API';

export default {
  components: {
    HistoryView,
  },
  data() {
    return {
      searchQuery: '',
      dateQuery: '',
      selectedEmployee: {
        roles: ""
      },
      roles: [], // Available roles from backend
      rolesLoading: false,
      rolesError: null,
      showPassword: false,
      showEditPassword: false,
      newEmployee: {
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
      departments: [], // Available departments from backend
      departmentsLoading: false,
      departmentsError: null,
      // keep a local fallback list for offline / initial UX (can be empty)
      employees: [],
      employeeTimes: []
    }
  },
  async mounted() {
    // fetch employee_info from Vuex store when component mounts
    if (this.$store && this.$store.dispatch) {
      this.$store.dispatch('fetch_employee_info').catch(err => {
        console.warn('Failed to fetch employee_info from store:', err)
      })
    }
    await Promise.all([
      this.fetchRoles(),
      this.fetchDepartments()
    ]);

    // Add event listener to clear selectedEmployee when modal is hidden
    const editModal = document.getElementById('editEmployeeModal');
    if (editModal) {
      editModal.addEventListener('hidden.bs.modal', () => {
        this.selectedEmployee = { roles: "" };
      });
    }
  },
  computed: {
    // use the store's employee_info if available, otherwise fall back to local `employees`
    sourceEmployees() {
      const storeList = this.$store && this.$store.state && Array.isArray(this.$store.state.employee_info)
        ? this.$store.state.employee_info
        : []
      const employees = storeList.length ? storeList : this.employees
      
      // Format dates for all employees to prevent date input errors
      return employees.map(emp => ({
        ...emp,
        dateHired: emp.dateHired ? this.formatDateForInput(emp.dateHired) : emp.dateHired
      }))
    },
    filteredList() {
      const q = (this.searchQuery || '').toLowerCase()
      return this.sourceEmployees.filter(item => {
        const matchesText = item.name?.toLowerCase().includes(q) ||
                           (item.employee_id|| '').toLowerCase().includes(q) ||
                           (item.roles || '').toLowerCase().includes(q) ||
                           (item.department || '').toLowerCase().includes(q)
        return matchesText
      })
    }
  },
  methods: {
    formatDateForInput(dateString) {
      if (!dateString) return dateString;
      try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        return date.toISOString().split('T')[0];
      } catch (error) {
        console.error('Error formatting date:', error);
        return dateString;
      }
    },
    async fetchRoles() {
      this.rolesLoading = true;
      this.rolesError = null;
      try {
        const response = await axios.get(`${API_URL}/api/employees/getRoles`);
        console.log('Fetched roles:', response.data);
        this.roles = response.data.roles.map(role => ({
          classification_id: role,
          role: role
        }));
      } catch (error) {
        console.error('Error fetching roles:', error);
        this.rolesError = 'Failed to load roles';
      } finally {
        this.rolesLoading = false;
      }
    },
    async fetchDepartments() {
      this.departmentsLoading = true;
      this.departmentsError = null;
      try {
        const response = await axios.get(`${API_URL}/api/employees/getDepartments`);
        console.log('Fetched departments:', response.data);
        this.departments = response.data.departments;
      } catch (error) {
        console.error('Error fetching departments:', error);
        this.departmentsError = 'Failed to load departments';
      } finally {
        this.departmentsLoading = false;
      }
    },
    HistoryView() {
      if (this.$router) {
        this.$router.push('/history');
      }
    },
    
    openHistoryModal() {
      console.log('History modal would open here');
    },
    
    openMapModal() {
      console.log('Map modal would open here');
    },

    clearFilters() {
      this.dateQuery = '';
      this.searchQuery = '';
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
    openAddEmployeeModal() {
      this.newEmployee = {
        // classificationId intentionally removed; backend will derive classification from role+department
        firstName: '',
        lastName: '',
        contactNo: '',
        email: '',
        address: '',
        idNumber: '',
        userType: 'Employee',
        dateHired: new Date().toISOString().split('T')[0],
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
    async addNewEmployee() {
      const requiredFields = [
        'firstName', 'lastName', 
        'contactNo', 'email', 'address', 'idNumber', 'dateHired',
        'leaveBalance', 'username', 'password', 'roles', 'department'
      ];
      
      for (let field of requiredFields) {
        if (!this.newEmployee[field]) {
          alert(`Please fill in the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field.`);
          return;
        }
      }

      // validate against store-backed list
      const existing = this.sourceEmployees || [];
      if (existing.some(emp => emp.username === this.newEmployee.username)) {
        alert('Username already exists. Please choose a different username.');
        return;
      }

      // send payload to store action (server should assign employee_id)
      const payload = {
        name: `${this.newEmployee.firstName} ${this.newEmployee.lastName}`,
        first_name: this.newEmployee.firstName,
        last_name: this.newEmployee.lastName,
        contact_no: this.newEmployee.contactNo,
        email: this.newEmployee.email,
        address: this.newEmployee.address,
        id_number: this.newEmployee.idNumber,
        user_type: this.newEmployee.userType,
        date_hired: this.newEmployee.dateHired,
        supervisor_name: this.newEmployee.supervisorName,
        leave_balance: this.newEmployee.leaveBalance,
        username: this.newEmployee.username,
        password: this.newEmployee.password,
        roles: this.newEmployee.roles,
        department: this.newEmployee.department,
        status: this.newEmployee.status
      };

      try {
        await this.$store.dispatch('add_employee', payload);
        const modal = bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal'));
        modal.hide();
        this.newEmployee = {}; // reset
        console.log(`Employee ${payload.name} added successfully.`)
      } catch (err) {
        console.error('Failed to add employee:', err)
        alert('Failed to add employee. See console for details.')
      }
    },

    openEditModal(employee) {
      // Create a deep copy to avoid reactivity issues
      const employeeCopy = JSON.parse(JSON.stringify(employee));
      
      const defaultEmployee = {
        employee_id: '',
        classification_id: '',
        first_name: '',
        last_name: '',
        contact_no: '',
        email: '',
        address: '',
        id_number: '',
        user_type: 'Employee',
        date_hired: new Date().toISOString().split('T')[0],
        supervisor_name: '',
        leave_balance: 20,
        username: '',
        password: '',
        roles: '',
        department: '',
        status: 'home'
      };
      
      // Merge with defaults, ensuring we don't have reactive references
      this.selectedEmployee = { ...defaultEmployee, ...employeeCopy };
      
      // Format the dateHired field to yyyy-MM-dd format for HTML date input
      if (this.selectedEmployee.dateHired) {
        this.selectedEmployee.dateHired = this.formatDateForInput(this.selectedEmployee.dateHired);
      }
      
      // Ensure all date-related fields are formatted
      if (this.selectedEmployee.date_hired) {
        this.selectedEmployee.date_hired = this.formatDateForInput(this.selectedEmployee.date_hired);
      }
      
      if (this.selectedEmployee.name && !this.selectedEmployee.firstName) {
        const nameParts = this.selectedEmployee.name.split(' ');
        this.selectedEmployee.firstName = nameParts[0] || '';
        this.selectedEmployee.lastName = nameParts.slice(1).join(' ') || '';
      }
      
      this.showEditPassword = false;
      const modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
      modal.show();
    },

    // Open View Times modal for given employee
    openViewTimesModal(employee) {
      this.selectedEmployee = employee || this.selectedEmployee;
      const el = document.getElementById('viewTimesModal');
      if (el && typeof window !== 'undefined' && window.bootstrap) {
        const modal = new window.bootstrap.Modal(el);
        modal.show();
      } else if (el && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(el);
        modal.show();
      } else {
        console.warn('Bootstrap modal instance or element not available for #viewTimesModal');
      }
    },

    // Open Delete Confirmation modal and set selected employee
    openDeleteModal(employee) {
      this.selectedEmployee = employee || this.selectedEmployee;
      const el = document.getElementById('deleteConfirmationModal');
      if (el && typeof window !== 'undefined' && window.bootstrap) {
        const modal = new window.bootstrap.Modal(el);
        modal.show();
      } else if (el && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(el);
        modal.show();
      } else {
        console.warn('Bootstrap modal instance or element not available for #deleteConfirmationModal');
      }
    },

    async saveEmployeeChanges() {
      const requiredFields = [
        'firstName', 'lastName', 
        'contactNo', 'email', 'address', 'idNumber', 'dateHired',
        'leaveBalance', 'username', 'roles', 'department'
      ];
      
      for (let field of requiredFields) {
        if (!this.selectedEmployee[field]) {
          alert(`Please fill in the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field.`);
          return;
        }
      }

      const existing = this.sourceEmployees || [];
      if (existing.some(emp => 
        emp.id !== this.selectedEmployee.id && 
        emp.employeeId === this.selectedEmployee.employeeId
      )) {
        alert('Employee ID already exists. Please use a unique ID.');
        return;
      }

      if (existing.some(emp => 
        emp.id !== this.selectedEmployee.id && 
        emp.username === this.selectedEmployee.username
      )) {
        alert('Username already exists. Please choose a different username.');
        return;
      }

      // Create clean payload with only the fields we need, avoiding duplicate/conflicting fields
      const payload = {
        id: this.selectedEmployee.id,
        firstName: this.selectedEmployee.firstName,
        lastName: this.selectedEmployee.lastName,
        contactNo: this.selectedEmployee.contactNo,
        email: this.selectedEmployee.email,
        address: this.selectedEmployee.address,
        idNumber: this.selectedEmployee.idNumber,
        userType: this.selectedEmployee.userType,
        dateHired: this.selectedEmployee.dateHired, // This is already formatted as yyyy-MM-dd
        supervisorName: this.selectedEmployee.supervisorName,
        leaveBalance: this.selectedEmployee.leaveBalance,
        username: this.selectedEmployee.username,
        roles: this.selectedEmployee.roles,
        department: this.selectedEmployee.department,
        status: this.selectedEmployee.status
      };

      // Only include password if it's not empty
      if (this.selectedEmployee.password && this.selectedEmployee.password.trim() !== '') {
        payload.password = this.selectedEmployee.password;
      }

      // Ensure name is synced
      payload.name = `${payload.firstName} ${payload.lastName}`;

      // Debug: Log what we're sending
      console.log('Sending employee update payload:', {
        roles: payload.roles,
        department: payload.department,
        userType: payload.userType
      });

      try {
        await this.$store.dispatch('edit_employee', payload);
        
        // Hide modal immediately after successful save
        const modal = bootstrap.Modal.getInstance(document.getElementById('editEmployeeModal'));
        if (modal) {
          modal.hide();
        }
        
        // Clear selectedEmployee to break any reactive connections
        this.selectedEmployee = {
          roles: ""
        };
        
        console.log(`Employee ${payload.name} updated successfully.`);
      } catch (err) {
        console.error('Failed to update employee:', err)
        alert('Failed to update employee. See console for details.')
      }
    },

    async confirmDelete() {
      if (this.selectedEmployee) {
        try {
          await this.$store.dispatch('delete_employee', this.selectedEmployee.id);
          const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
          modal.hide();
          console.log(`Employee ${this.selectedEmployee.name} has been deleted.`);
        } catch (err) {
          console.error('Failed to delete employee:', err)
          alert('Failed to delete employee. See console for details.')
        }
      }
    },

    // ...existing code...
  },
}
</script>

<style scoped>
/* Updated styles for the new layout */
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
  border: 1px solid #00C0AA;
  border-radius: 8px;
  background-color: #f8f9fa;
}

.search-input {
  width: 100%;
  border: 1px solid #00C0AA;
  font-size: 0.875rem;
  padding: 6px 12px;
}

.search-input:focus {
  border-color: #00C0AA;
  box-shadow: 0 0 0 0.2rem rgba(0, 192, 170, 0.25);
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