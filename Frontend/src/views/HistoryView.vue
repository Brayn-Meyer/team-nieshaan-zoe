<template>
    <NavComp/>
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
        <HistoryTable />
      </div>
    </div>
  </div>
</template>
<script>
import HistoryFilters from "@/components/HistoryFilters.vue";
import HistoryTable from "@/components/HistoryTable.vue";
import NavComp from "@/components/NavComp.vue";
export default {
  name: "HistoryView",
  components: { HistoryFilters, HistoryTable, NavComp },
  data() {
    return {
      filters: { date: "", name: "", status: "", employeeId: "" },
    };
  },
  computed: {
    filteredHistory() {
      // Return data from store (will be filtered by backend)
      return this.$store.state.history_info || [];
    }
  },
  methods: {
    async updateFilters(newFilters) {
      this.filters = { ...newFilters };
      
      // Check if any filters are applied
      const hasFilters = Object.values(newFilters).some(value => value !== '');
      
      if (hasFilters) {
        // Apply filters via backend
        try {
          await this.$store.dispatch('apply_history_filter', newFilters);
        } catch (error) {
          console.error('Failed to apply filters:', error);
          // Fallback to showing all data
          await this.$store.dispatch('fetch_history_info');
        }
      } else {
        // No filters, fetch all data
        await this.$store.dispatch('fetch_history_info');
      }
    },
    downloadSheet() {
      try {
        const data = this.filteredHistory;
        const csvContent =
          "data:text/csv;charset=utf-8," +
          [
            [
              "Employee ID",
              "Date", 
              "First Name",
              "Last Name",
              "Full Name",
              "Clock In",
              "Tea Out",
              "Tea In", 
              "Lunch Out",
              "Lunch In",
              "Clock Out",
            ].join(","),
            ...data.map((r) =>
              [
                r.employee_id,
                r.work_date,
                r.first_name,
                r.last_name,
                `${r.first_name} ${r.last_name}`,
                r.work_clockin || '-',
                r.tea_clockout || '-',
                r.tea_clockin || '-',
                r.lunch_clockout || '-',
                r.lunch_clockin || '-',
                r.work_clockout || '-',
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
</style>