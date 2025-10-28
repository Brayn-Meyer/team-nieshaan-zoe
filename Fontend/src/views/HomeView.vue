<template>
  <!-- DASHBOARD CONTAINER -->
  <div>
    <div class="dashboard-container">
      <main class="dashboard-main">
        <AttendanceCard
          title="Total Employees"
          icon="fa-solid fa-users"
          :value=attendanceData.total
          description="Total registered employees"
          change="+5%"
          type="total"
        />

        <AttendanceCard
          title="Clock In"
          icon="fa-solid fa-door-open"
          :value=attendanceData.checkedIn
          description="Currently clocked in"
          change="+3%"
          type="checked-in"
        />

        <AttendanceCard
          title="Clock Out"
          icon="fa-solid fa-door-closed"
          :value=attendanceData.checkedOut
          description="Clocked out today"
          change="-1%"
          type="checked-out"
        />

        <AttendanceCard
          title="Absent"
          icon="fa-solid fa-user-xmark"
          :value=attendanceData.absent
          description="Not present today"
          change="0%"
          type="absent"
        />
      </main>
    </div>
  </div>
</template>

<script>
import AttendanceCard from "@/components/AttendanceCard.vue";
import socket from "@/sockets/socket.js";
import axios from 'axios';
import API_URL from "@/API.js";

export default {
  name: "HomeView",
  components: { AttendanceCard },

  data() {
    return {
      attendanceData: {
        total: 0,
        checkedIn: 0,
        checkedOut: 0,
        absent: 0
      }
    }
  },

  async mounted() {

    await this.fetchAttendanceData();

    socket.on("kpiUpdate", (data) => {
      this.attendanceData = data;
    });
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
}

.dashboard-main {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
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
