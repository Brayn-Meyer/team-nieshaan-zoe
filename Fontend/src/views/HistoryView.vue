<template>
    <NavComp/>
    <!-- Help Button -->
    <button @click="showUserGuide = true" class="help-btn">
      <i class="fa-solid fa-circle-question"></i>
      Help Guide
    </button>
    
    <br><br><br><br><br>
  <div class="container-fluid py-3">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="flex-grow-1 text-center">
            <h2 class="mb-1">Attendance History</h2>
            <br>
            <p class="mb-0">Filter Employees History</p>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <HistoryFilters
          :filters="filters"
          @update-filters="updateFilters"
        />
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-12 text-end">
        <button class="btn download btn-sm" @click="downloadSheet">
          <i class="bi bi-download me-1"></i>Download
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <HistoryTable :records="filteredHistory" />
      </div>
    </div>

    <!-- User Guide Component -->
    <HistoryGuide 
      :showGuide="showUserGuide" 
      @close-guide="showUserGuide = false"
      @finish-guide="showUserGuide = false"
    />
  </div>
</template>

<script>
import HistoryFilters from "@/components/HistoryFilters.vue";
import HistoryTable from "@/components/HistoryTable.vue";
import NavComp from "@/components/NavComp.vue";
import HistoryGuide from "@/components/HistoryGuide.vue";

export default {
  name: "HistoryView",
  components: { 
    HistoryFilters, 
    HistoryTable, 
    NavComp,
    HistoryGuide 
  },
  data() {
    return {
      showUserGuide: false,
      filters: { date: "", name: "", status: "", employeeId: "" },
      history: [
        // Your existing history data remains exactly the same
        {
          id: 1,
          employeeId: "EMP001",
          date: "2025-10-23",
          firstName: "John",
          lastName: "Adams",
          status: "active",
          clockIn: "08:00",
          teaOut: "10:00",
          teaIn: "10:15",
          lunchOut: "12:30",
          lunchIn: "13:00",
          clockOut: "17:00",
        },
        // ... rest of your history data
      ],
    };
  },
  computed: {
    filteredHistory() {
      return this.history.filter(record => {
        if (this.filters.date && record.date !== this.filters.date) {
          return false;
        }
        if (this.filters.name) {
          const searchTerm = this.filters.name.toLowerCase();
          const fullName = `${record.firstName} ${record.lastName}`.toLowerCase();
          const firstName = record.firstName.toLowerCase();
          const lastName = record.lastName.toLowerCase();
          if (!fullName.includes(searchTerm) &&
              !firstName.includes(searchTerm) &&
              !lastName.includes(searchTerm)) {
            return false;
          }
        }
        if (this.filters.employeeId &&
            !record.employeeId.toLowerCase().includes(this.filters.employeeId.toLowerCase())) {
          return false;
        }
        if (this.filters.status && record.status !== this.filters.status) {
          return false;
        }
        return true;
      });
    }
  },
  methods: {
    updateFilters(newFilters) {
      this.filters = { ...newFilters };
    },
    downloadSheet() {
      try {
        const csvContent =
          "data:text/csv;charset=utf-8," +
          [
            [
              "Employee ID",
              "Date",
              "First Name",
              "Last Name",
              "Full Name",
              "Status",
              "Clock In",
              "Tea Out",
              "Tea In",
              "Lunch Out",
              "Lunch In",
              "Clock Out",
            ].join(","),
            ...this.filteredHistory.map((r) =>
              [
                r.employeeId,
                r.date,
                r.firstName,
                r.lastName,
                `${r.firstName} ${r.lastName}`,
                r.status,
                r.clockIn,
                r.teaOut,
                r.teaIn,
                r.lunchOut,
                r.lunchIn,
                r.clockOut,
              ].join(",")
            ),
          ].join("\n");
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "attendance_history.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        alert("Table exported to CSV (you can import to Google Sheets).");
      } catch (error) {
        console.error("Export error:", error);
        alert("Failed to export data.");
      }
    },
  },
  mounted() {
    // Auto-show guide on first visit to history page
    const hasSeenHistoryGuide = localStorage.getItem('hasSeenHistoryGuide');
    if (!hasSeenHistoryGuide) {
      this.showUserGuide = true;
      localStorage.setItem('hasSeenHistoryGuide', 'true');
    }

    // Add keyboard shortcut (Ctrl + H) to open history guide
    document.addEventListener('keydown', this.handleKeyPress);
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleKeyPress);
  },
  methods: {
    handleKeyPress(event) {
      if (event.ctrlKey && event.key === 'h') {
        event.preventDefault();
        this.showUserGuide = true;
      }
    },
    // Your existing methods remain the same
    updateFilters(newFilters) {
      this.filters = { ...newFilters };
    },
    downloadSheet() {
      // Your existing download method
    }
  }
};
</script>

<style scoped>
.container-fluid {
  padding-left: 1rem;
  padding-right: 1rem;
}
.download{
  color: white;
  background-color: #2EB28A !important;
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