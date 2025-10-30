<template>
  <div class="table-container">
    <table class="time-log-table">
      <thead>
        <tr>
          <th>Employee Name</th>
          <th>Employee ID</th>
          <th>Status</th>
          <th>Hours Worked</th>
          <th>Hours Owed</th>
          <th>Overtime</th>
          <th>Indicator</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employee in filteredEmployees" :key="employee.id">
          <td>{{ employee.name }}</td>
          <td>{{ employee.id }}</td>
          <td>{{ employee.status }}</td>
          <td>{{ employee.hoursWorked }}h</td>
          <td>{{ employee.hoursOwed > 0 ? employee.hoursOwed + 'h' : '-' }}</td>
          <td>{{ employee.overtime > 0 ? employee.overtime + 'h' : '-' }}</td>
          <td>
            <span
              class="indicator"
              :class="{
                'green': employee.indicator === 'green',
                'yellow': employee.indicator === 'yellow',
                'red': employee.indicator === 'red'
              }"
              @click="handleIndicatorClick(employee)"
            ></span>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="showPopup" class="popup-overlay">
      <div class="popup-container">
        <div class="popup-content">
          <h3>Hours are balanced.</h3>
          <p>Confirm changes for {{ popupEmployee?.name }}?</p>
          <div class="popup-buttons">
            <button class="popup-btn popup-btn-no" @click="cancelChange">No</button>
            <button class="popup-btn popup-btn-yes" @click="confirmChange">Yes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TimeLog',
  props: {
    filterData: {
      type: Object,
      default: () => ({ search: '', filter: null })
    }
  },
  data() {
    return {
      employees: [
        { name: "John Smith", id: "E101", status: "Active", hoursWorked: 45, hoursOwed: 0, overtime: 0, indicator: "green" },
        { name: "Sarah Johnson", id: "E102", status: "Active", hoursWorked: 42, hoursOwed: 3, overtime: 3, indicator: "yellow" },
        { name: "Michael Brown", id: "E103", status: "Active", hoursWorked: 40, hoursOwed: 5, overtime: 0, indicator: "red" },
        { name: "Emily Davis", id: "E104", status: "Active", hoursWorked: 38, hoursOwed: 2, overtime: 0, indicator: "red" },
        { name: "David Wilson", id: "E105", status: "Active", hoursWorked: 45, hoursOwed: 5, overtime: 5, indicator: "yellow" },
        { name: "Jennifer Lee", id: "E106", status: "Active", hoursWorked: 40, hoursOwed: 0, overtime: 0, indicator: "green" },
        { name: "Robert Taylor", id: "E107", status: "Active", hoursWorked: 43, hoursOwed: 3, overtime: 3, indicator: "yellow" },
        { name: "Lisa Anderson", id: "E108", status: "Inactive", hoursWorked: 0, hoursOwed: 8, overtime: 0, indicator: "red" },
        { name: "James Martinez", id: "E109", status: "Active", hoursWorked: 47, hoursOwed: 7, overtime: 7, indicator: "yellow" },
        { name: "Amanda White", id: "E110", status: "Active", hoursWorked: 39, hoursOwed: 1, overtime: 0, indicator: "red" },
        { name: "Christopher Garcia", id: "E111", status: "Active", hoursWorked: 41, hoursOwed: 0, overtime: 1, indicator: "green" },
        { name: "Michelle Rodriguez", id: "E112", status: "Active", hoursWorked: 37, hoursOwed: 3, overtime: 0, indicator: "red" }
      ],
      showPopup: false,
      popupEmployee: null
    }
  },
  computed: {
    filteredEmployees() {
      let filtered = this.employees;
      
      if (this.filterData.search) {
        const query = this.filterData.search.toLowerCase();
        filtered = filtered.filter(employee => 
          employee.name.toLowerCase().includes(query) || 
          employee.id.toLowerCase().includes(query)
        );
      }

      if (this.filterData.filter) {
        filtered = filtered.filter(employee => 
          employee.indicator === this.filterData.filter
        );
      }

      return filtered;
    }
  },
  methods: {
    handleIndicatorClick(employee) {
      if (employee.indicator === 'yellow') {
        this.popupEmployee = employee;
        this.showPopup = true;
      }
    },
    confirmChange() {
      if (this.popupEmployee) {
        this.popupEmployee.indicator = 'green';
      }
      this.closePopup();
    },
    cancelChange() {
      this.closePopup();
    },
    closePopup() {
      this.showPopup = false;
      this.popupEmployee = null;
    }
  }
}
</script>

<style scoped>
.table-container {
  margin: 2rem;
  overflow-x: auto;
  border-radius: 8px;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  position: relative;
}
.time-log-table {
  width: 100%;
  border-collapse: collapse;
  font-family: 'Poppins', sans-serif;
}
.time-log-table thead {
  background-color: #2EB28A;
  color: white;
}
.time-log-table th, .time-log-table td {
  padding: 14px 20px;
  border-bottom: 1px solid #E0E0E0;
}
.indicator {
  display: inline-block;
  width: 14px;
  height: 14px;
  border-radius: 50%;
  cursor: pointer;
  transition: transform 0.2s;
}
.indicator:hover {
  transform: scale(1.2);
}
.indicator.green {
  background-color: #00C851;
}
.indicator.yellow {
  background-color: #FFBB33;
}
.indicator.red {
  background-color: #FF4444;
}

.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.popup-container {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  min-width: 400px;
  text-align: center;
}

.popup-content h3 {
  margin: 0 0 10px 0;
  font-size: 20px;
  font-weight: 600;
  color: #333;
  font-family: 'Poppins', sans-serif;
}

.popup-content p {
  margin: 0 0 25px 0;
  font-size: 16px;
  color: #666;
  font-family: 'Poppins', sans-serif;
}

.popup-buttons {
  display: flex;
  gap: 15px;
  justify-content: center;
}

.popup-btn {
  padding: 12px 30px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-family: 'Poppins', sans-serif;
  min-width: 100px;
}

.popup-btn-no {
  background-color: #f8f9fa;
  color: #333;
  border: 2px solid #dee2e6;
}

.popup-btn-no:hover {
  background-color: #e9ecef;
  border-color: #adb5bd;
}

.popup-btn-yes {
  background-color: #2EB28A;
  color: white;
  border: 2px solid #2EB28A;
}

.popup-btn-yes:hover {
  background-color: #26997a;
  border-color: #26997a;
}
</style>