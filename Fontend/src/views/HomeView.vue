<template>
  <!-- DASHBOARD CONTAINER -->
  <div>
    <div class="dashboard-container">
      <main class="dashboard-main">
        <AttendanceCard
          title="Total Employees"
          icon="fa-solid fa-users"
          :value="120"
          description="Total registered employees"
          change="+5%"
          type="total"
          :isPositive="true"
        />

        <AttendanceCard
          title="Clock In"
          icon="fa-solid fa-door-open"
          :value="95"
          description="Currently clocked in"
          change="+3%"
          type="checked-in"
          :isPositive="true"
        />

        <AttendanceCard
          title="Clock Out"
          icon="fa-solid fa-door-closed"
          :value="20"
          description="Clocked out today"
          change="-1%"
          type="checked-out"
          :isPositive="false"
        />

        <AttendanceCard
          title="Absent"
          icon="fa-solid fa-user-xmark"
          :value="5"
          description="Not present today"
          change="0%"
          type="absent"
          :isPositive="false"
        />
      </main>
    </div>
  </div>
</template>

<script>
import AttendanceCard from "@/components/AttendanceCard.vue";

export default {
  name: "HomeView",
  components: { AttendanceCard },
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
