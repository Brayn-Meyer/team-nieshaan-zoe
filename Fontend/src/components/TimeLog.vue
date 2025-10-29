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
</template>

<script setup>
const employees = [
  { name: "Employee Names", id: "E123", status: "Active", hoursWorked: "45h", hoursOwed: "-", overtime: "-", indicator: "green" },
  { name: "Employee Names", id: "E124", status: "Active", hoursWorked: "42h", hoursOwed: "-", overtime: "3h", indicator: "yellow" },
  { name: "Employee Names", id: "E125", status: "Active", hoursWorked: "40h", hoursOwed: "5h", overtime: "-", indicator: "red" },
  { name: "Employee Names", id: "E126", status: "Active", hoursWorked: "45h", hoursOwed: "-", overtime: "-", indicator: "green" }
];
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
</style>
