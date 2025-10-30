<template>
  <NavComp @theme-changed="handleThemeChange"/>
  <!-- DASHBOARD CONTAINER -->
  <div :class="{ 'dark-mode': isDarkMode }">
    <div class="dashboard-container">
      <main class="dashboard-main">
        <AttendanceCard
          title="Total Employees"
          icon="fa-solid fa-users"
          :value=attendanceData.total
          description="Total registered employees"
          change="+5%"
          type="total"
          :isDarkMode="isDarkMode"
        />

        <AttendanceCard
          title="Clock In"
          icon="fa-solid fa-door-open"
          :value=attendanceData.checkedIn
          description="Currently clocked in"
          change="+3%"
          type="checked-in"
          :isDarkMode="isDarkMode"
        />

        <AttendanceCard
          title="Clock Out"
          icon="fa-solid fa-door-closed"
          :value=attendanceData.checkedOut
          description="Clocked out today"
          change="-1%"
          type="checked-out"
          :isDarkMode="isDarkMode"
        />

        <AttendanceCard
          title="Absent"
          icon="fa-solid fa-user-xmark"
          :value=attendanceData.absent
          description="Not present today"
          change="0%"
          type="absent"
          :isDarkMode="isDarkMode"
        />
      </main>
      <br>
      <br>
      <br>
      <br>
      <EmployeeTable :isDarkMode="isDarkMode"/>
    </div>
  </div>
</template>

<script>
import AttendanceCard from "@/components/AttendanceCard.vue";
import EmployeeTable from "@/components/EmployeeTable.vue";
import NavComp from "@/components/NavComp.vue";
import axios from "axios";
import API_URL from '../API';
import socket from '../sockets/socket';

export default {
  name: "HomeView",
  components: {
    AttendanceCard,
    EmployeeTable,
    NavComp
  },
  data() {
    return {
      attendanceData: {
        total: 0,
        checkedIn: 0,
        checkedOut: 0,
        absent: 0
      },
      isDarkMode: false
    }
  },
  async mounted() {
    await this.fetchAttendanceData();

    socket.on("kpiUpdate", (data) => {
      this.attendanceData = data;
    });

    // Check for saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      this.isDarkMode = true;
    }
  },
  beforeUnmount(){
    socket.off("kpiUpdate");
  },
  methods:{
    async fetchAttendanceData() {
      try {
        const response = await axios.get(`${API_URL}/api/admin/cards/allKpiData`);
        this.attendanceData = response.data;
      } catch (error) {
        console.error("Error fetching attendance data:", error);
      }
    },
    handleThemeChange(isDarkMode) {
      this.isDarkMode = isDarkMode;
    }
  }
};
</script>

<style scoped>
.dashboard-container {
  margin-top: 30px;
  background: #f8fafc;
  min-height: 100vh;
  padding: 120px 60px 50px; 
  transition: background-color 0.3s ease, color 0.3s ease;
}

.dark-mode .dashboard-container {
  background: #121212;
  color: #e0e0e0;
}

.dashboard-main {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
}

/* Dark mode text colors */
.dark-mode {
  color: #e0e0e0;
}

/* Global styles for child components in dark mode */
:deep(.card) {
  transition: all 0.3s ease;
}

:deep(.table-container) {
  transition: all 0.3s ease;
}

.dark-mode :deep(.card) {
  background: #2d2d2d !important;
  color: #e0e0e0 !important;
  border-color: #404040 !important;
}

.dark-mode :deep(.table-container) {
  background: #2d2d2d !important;
  color: #e0e0e0 !important;
}

.dark-mode :deep(.table-header) {
  background: #363636 !important;
  color: #e0e0e0 !important;
}

.dark-mode :deep(.table-row) {
  background: #2d2d2d !important;
  color: #e0e0e0 !important;
  border-color: #404040 !important;
}

.dark-mode :deep(.table-row:hover) {
  background: #363636 !important;
}

@media (max-width: 576px) {
  .app-navbar-inner {
    padding: 12px 16px;
  }

  .nav-left {
    font-size: 1.3rem;
  }

  .logout-btn {
    padding: 6px 12px;
  }

  .dashboard-container {
    padding: 100px 20px;
  }
}
</style>