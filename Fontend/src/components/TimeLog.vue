<template>
  <div class="table-container">
    <div class="table-wrapper">
      <table class="time-log-table">
        <thead class="th">
          <tr>
            <th>Employee Name</th>
            <th>Employee ID</th>
            <th>Hours Worked</th>
            <th>Hours Owed</th>
            <th>Overtime</th>
            <th>Indicator</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee in filteredEmployees" :key="employee.id" class="table-row">
            <td data-label="Employee Name">{{ employee.name }}</td>
            <td data-label="Employee ID">{{ employee.id }}</td>
            <td data-label="Hours Worked">{{ employee.hoursWorked }}h</td>
            <td data-label="Hours Owed">{{ employee.hoursOwed > 0 ? employee.hoursOwed + 'h' : '-' }}</td>
            <td data-label="Overtime">{{ employee.overtime > 0 ? employee.overtime + 'h' : '-' }}</td>
            <td data-label="Indicator">
              <span
                class="indicator"
                :class="{
                  'green': employee.indicator === 'green',
                  'red': employee.indicator === 'red'
                }"
                @click="handleIndicatorClick(employee)"
              ></span>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div v-if="filteredEmployees.length === 0" class="no-results">
        <p>No employees found matching your criteria.</p>
      </div>
    </div>

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
      default: () => ({ search: '', filter: null, week: null })
    }
  },
  data() {
    return {
      employees: [
        { name: "John Smith", id: "E101", hoursWorked: 41, hoursOwed: 0, overtime: 1, indicator: "green" },
        { name: "Sarah Johnson", id: "E102", hoursWorked: 39, hoursOwed: 1, overtime: 0, indicator: "red" },
        { name: "Michael Brown", id: "E103", hoursWorked: 40, hoursOwed: 5, overtime: 0, indicator: "red" },
        { name: "Emily Davis", id: "E104", hoursWorked: 38, hoursOwed: 2, overtime: 0, indicator: "red" },
        { name: "David Wilson", id: "E105", hoursWorked: 35, hoursOwed: 5, overtime: 0, indicator: "red" },
        { name: "Jennifer Lee", id: "E106", hoursWorked: 41, hoursOwed: 0, overtime: 1, indicator: "green" },
        { name: "Robert Taylor", id: "E107", hoursWorked: 43, hoursOwed: 7, overtime: 0, indicator: "red" },
        { name: "Lisa Anderson", id: "E108", hoursWorked: 0, hoursOwed: 40, overtime: 0, indicator: "red" },
        { name: "James Martinez", id: "E109", hoursWorked: 33, hoursOwed: 7, overtime: 0, indicator: "red" },
        { name: "Amanda White", id: "E110", hoursWorked: 39, hoursOwed: 1, overtime: 0, indicator: "red" },
        { name: "Christopher Garcia", id: "E111", hoursWorked: 41, hoursOwed: 0, overtime: 1, indicator: "green" },
        { name: "Michelle Rodriguez", id: "E112", hoursWorked: 37, hoursOwed: 3, overtime: 0, indicator: "red" }
      ],
      showPopup: false,
      popupEmployee: null
    }
  },
  computed: {
    filteredEmployees() {
      let filtered = this.employees;
      
      // Search filter
      if (this.filterData.search) {
        const query = this.filterData.search.toLowerCase();
        filtered = filtered.filter(employee => 
          employee.name.toLowerCase().includes(query) || 
          employee.id.toLowerCase().includes(query)
        );
      }

      // Color filter - fix: check if filter is not null
      if (this.filterData.filter) {
        filtered = filtered.filter(employee => 
          employee.indicator === this.filterData.filter
        );
      }

      // Week filter
      if (this.filterData.week) {
        console.log('Selected week:', this.filterData.week);
        // Add your week-based filtering logic here
      }

      return filtered;
    }
  },
  methods: {
    handleIndicatorClick(employee) {
      if (employee.indicator === 'red') {
        this.popupEmployee = employee;
        this.showPopup = true;
      }
    },
    confirmChange() {
      if (this.popupEmployee) {
        this.popupEmployee.indicator = 'green';
        // Force re-render of the computed property
        this.$forceUpdate();
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
  margin: 1rem auto;
  border-radius: 8px;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  position: relative;
  max-width: 1200px;
  width: 95%;
}

.table-wrapper {
  overflow-x: auto;
  border-radius: 8px;
}

.time-log-table {
  width: 100%;
  border-collapse: collapse;
  font-family: 'Poppins', sans-serif;
  background-color: white;
  min-width: 600px;
}

.time-log-table thead {
  background-color: #2EB28A;
  color: white;
}

.time-log-table th, .time-log-table td {
  padding: 30px 20px;
  border-bottom: 1px solid #E0E0E0;
}


.table-row:hover {
  background-color: #f8f9fa;
}

.indicator {
  display: inline-block;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  cursor: pointer;
  transition: transform 0.2s;
  border: 2px solid transparent;
}

.indicator:hover {
  transform: scale(1.2);
}

.indicator.green {
  background-color: #00C851;
}

.indicator.red {
  background-color: #FF4444;
}

.no-results {
  text-align: center;
  padding: 40px;
  color: #666;
  font-family: 'Poppins', sans-serif;
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
  .table-container {
    margin: 1rem;
    margin-bottom: 1rem;
  }
  
  .time-log-table {
    min-width: 100%;
  }
  
  .time-log-table thead {
    display: none;
  }
  
  .time-log-table tbody tr {
    display: block;
    margin-bottom: 15px;
    border: 1px solid #E0E0E0;
    border-radius: 8px;
    padding: 10px;
    background: white;
  }
  
  .time-log-table tbody tr td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    border-bottom: 1px solid #f0f0f0;
    text-align: right;
  }
  
  .time-log-table tbody tr td:last-child {
    border-bottom: none;
  }
  
  .time-log-table tbody tr td::before {
    content: attr(data-label);
    font-weight: 600;
    text-align: left;
    color: #333;
  }
  
  .time-log-table tbody tr td:last-child {
    justify-content: flex-end;
    gap: 10px;
  }
  
  .time-log-table tbody tr td:last-child::before {
    content: "Indicator";
  }
}

@media (max-width: 480px) {
  .table-container {
    margin: 0.5rem;
    margin-bottom: 1rem;
  }
  
  .time-log-table tbody tr td {
    padding: 8px 12px;
    font-size: 14px;
  }
  
  .time-log-table tbody tr {
    margin-bottom: 10px;
    padding: 8px;
  }
}


@media (max-width: 480px) {
  .popup-container {
    padding: 20px;
    min-width: 250px;
  }
  
  .popup-buttons {
    flex-direction: column;
    gap: 10px;
  }
  
  .popup-btn {
    width: 100%;
  }
}
</style>