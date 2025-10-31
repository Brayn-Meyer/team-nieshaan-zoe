<template>
  <div>
    <!-- Help Button -->
    <button @click="showUserGuide = true" class="help-btn">
      <i class="fa-solid fa-circle-question"></i>
      Help Guide
    </button>

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
          <tr v-for="employee in employees" :key="employee.id">
            <td>{{ employee.name }}</td>
            <td>{{ employee.id }}</td>
            <td>{{ employee.status }}</td>
            <td>{{ employee.hoursWorked }}</td>
            <td>{{ employee.hoursOwed || '-' }}</td>
            <td>{{ employee.overtime || '-' }}</td>
            <td>
              <span
                class="indicator"
                :class="{
                  'green': employee.indicator === 'green',
                  'yellow': employee.indicator === 'yellow',
                  'red': employee.indicator === 'red'
                }"
              ></span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Time Log Guide Component -->
    <TimeLogGuide 
      :showGuide="showUserGuide" 
      @close-guide="showUserGuide = false"
      @finish-guide="showUserGuide = false"
    />
  </div>
</template>

<script>
import TimeLogGuide from "@/components/TimeLogGuide.vue";

export default {
  name: "TimeLogView",
  components: {
    TimeLogGuide
  },
  data() {
    return {
      showUserGuide: false,
      employees: [
        { name: "Employee Names", id: "E123", status: "Active", hoursWorked: "45h", hoursOwed: "-", overtime: "-", indicator: "green" },
        { name: "Employee Names", id: "E124", status: "Active", hoursWorked: "42h", hoursOwed: "-", overtime: "3h", indicator: "yellow" },
        { name: "Employee Names", id: "E125", status: "Active", hoursWorked: "40h", hoursOwed: "5h", overtime: "-", indicator: "red" },
        { name: "Employee Names", id: "E126", status: "Active", hoursWorked: "45h", hoursOwed: "-", overtime: "-", indicator: "green" }
      ]
    };
  },
  mounted() {
    // Auto-show guide on first visit
    const hasSeenTimeLogGuide = localStorage.getItem('hasSeenTimeLogGuide');
    if (!hasSeenTimeLogGuide) {
      this.showUserGuide = true;
      localStorage.setItem('hasSeenTimeLogGuide', 'true');
    }

    // Add keyboard shortcut (Ctrl + T) to open guide
    document.addEventListener('keydown', this.handleKeyPress);
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleKeyPress);
  },
  methods: {
    handleKeyPress(event) {
      if (event.ctrlKey && event.key === 't') {
        event.preventDefault();
        this.showUserGuide = true;
      }
    }
  }
};
</script>

<style scoped>
.table-container {
  margin: 1rem;
  overflow-x: auto;
  border-radius: 8px;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.time-log-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
  font-family: 'Poppins', sans-serif;
}

.time-log-table thead {
  background-color: #2EB28A;
  color: white;
}

.time-log-table th, .time-log-table td {
  padding: 14px 20px;
  border-bottom: 1px solid #e0e0e0;
}

.indicator {
  display: inline-block;
  width: 14px;
  height: 14px;
  border-radius: 50%;
}

.indicator.green {
  background-color: #00c851;
}

.indicator.yellow {
  background-color: #ffbb33;
}

.indicator.red {
  background-color: #ff4444;
}

/* Help Button Styles */
.help-btn {
  position: fixed;
  top: 100px;
  right: 30px;
  background: #10b981;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 25px;
  cursor: pointer;
  font-weight: 600;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
  font-family: inherit;
}

.help-btn:hover {
  background: #059669;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
}

.help-btn i {
  font-size: 16px;
}

@media (max-width: 576px) {
  .help-btn {
    top: 80px;
    right: 20px;
    padding: 10px 16px;
    font-size: 0.9em;
  }
}
</style>