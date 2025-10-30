<template>
  <NavComp/>
  <!-- Help Button -->
  <button @click="showUserGuide = true" class="help-btn">
    <i class="fa-solid fa-circle-question"></i>
    Help Guide
  </button>

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
      <br>
      <br>
      <br>
      <br>
      <EmployeeTable />
    </div>

    <!-- User Guide Component -->
    <UserGuide 
      :showGuide="showUserGuide" 
      @close-guide="showUserGuide = false"
      @finish-guide="showUserGuide = false"
    />
  </div>
</template>

<script>
import AttendanceCard from "@/components/AttendanceCard.vue";
import EmployeeTable from "@/components/EmployeeTable.vue";
import NavComp from "@/components/NavComp.vue";
import UserGuide from "@/components/UserGuide.vue";

export default {
  name: "HomeView",
  components: {
    AttendanceCard,
    EmployeeTable,
    NavComp,
    UserGuide
  },
  data() {
    return {
      showUserGuide: false
    };
  },
  mounted() {
    // Auto-show guide on first visit
    const hasSeenGuide = localStorage.getItem('hasSeenAttendanceGuide');
    if (!hasSeenGuide) {
      this.showUserGuide = true;
      localStorage.setItem('hasSeenAttendanceGuide', 'true');
    }

    // Add keyboard shortcut (Ctrl + /) to open guide
    document.addEventListener('keydown', this.handleKeyPress);
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleKeyPress);
  },
  methods: {
    handleKeyPress(event) {
      if (event.ctrlKey && event.key === '/') {
        event.preventDefault();
        this.showUserGuide = true;
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