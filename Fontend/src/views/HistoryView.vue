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
          @update-filters="onUpdateFilters"
        />
      </div>
    </div>
    <!-- Added spacing class here -->
    <div class="row mb-3 mt-4 mt-md-3 mt-sm-2">
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
      history: [
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
        {
          id: 2,
          employeeId: "EMP002",
          date: "2025-10-23",
          firstName: "Sarah",
          lastName: "Brown",
          status: "active",
          clockIn: "08:45",
          teaOut: "10:00",
          teaIn: "10:20",
          lunchOut: "12:30",
          lunchIn: "13:15",
          clockOut: "17:00",
        },
        {
          id: 3,
          employeeId: "EMP003",
          date: "2025-10-24",
          firstName: "Michael",
          lastName: "Carter",
          status: "active",
          clockIn: "07:55",
          teaOut: "10:05",
          teaIn: "10:18",
          lunchOut: "12:25",
          lunchIn: "13:05",
          clockOut: "16:58",
        },
        {
          id: 4,
          employeeId: "EMP004",
          date: "2025-10-24",
          firstName: "Emily",
          lastName: "Davis",
          status: "on-leave",
          clockIn: "-",
          teaOut: "-",
          teaIn: "-",
          lunchOut: "-",
          lunchIn: "-",
          clockOut: "-",
        },
        {
          id: 5,
          employeeId: "EMP005",
          date: "2025-10-25",
          firstName: "David",
          lastName: "Evans",
          status: "active",
          clockIn: "09:15",
          teaOut: "10:30",
          teaIn: "10:45",
          lunchOut: "13:00",
          lunchIn: "13:45",
          clockOut: "17:30",
        },
        {
          id: 6,
          employeeId: "EMP006",
          date: "2025-10-25",
          firstName: "Lisa",
          lastName: "Foster",
          status: "active",
          clockIn: "08:00",
          teaOut: "10:10",
          teaIn: "10:22",
          lunchOut: "12:35",
          lunchIn: "13:10",
          clockOut: "17:05",
        },
        {
          id: 7,
          employeeId: "EMP001",
          date: "2025-10-26",
          firstName: "John",
          lastName: "Adams",
          status: "active",
          clockIn: "08:02",
          teaOut: "10:03",
          teaIn: "10:17",
          lunchOut: "12:28",
          lunchIn: "13:02",
          clockOut: "16:59",
        },
        {
          id: 8,
          employeeId: "EMP007",
          date: "2025-10-26",
          firstName: "Robert",
          lastName: "Graham",
          status: "inactive",
          clockIn: "08:35",
          teaOut: "10:40",
          teaIn: "10:55",
          lunchOut: "13:10",
          lunchIn: "13:50",
          clockOut: "17:25",
        },
        {
          id: 9,
          employeeId: "EMP008",
          date: "2025-10-27",
          firstName: "Jennifer",
          lastName: "Harris",
          status: "active",
          clockIn: "07:58",
          teaOut: "10:02",
          teaIn: "10:16",
          lunchOut: "12:27",
          lunchIn: "13:01",
          clockOut: "17:00",
        },
        {
          id: 10,
          employeeId: "EMP002",
          date: "2025-10-27",
          firstName: "Sarah",
          lastName: "Brown",
          status: "on-leave",
          clockIn: "-",
          teaOut: "-",
          teaIn: "-",
          lunchOut: "-",
          lunchIn: "-",
          clockOut: "-",
        },
        {
          id: 11,
          employeeId: "EMP009",
          date: "2025-10-28",
          firstName: "Thomas",
          lastName: "Irwin",
          status: "active",
          clockIn: "08:01",
          teaOut: "10:04",
          teaIn: "10:19",
          lunchOut: "12:29",
          lunchIn: "13:03",
          clockOut: "17:02",
        },
        {
          id: 12,
          employeeId: "EMP010",
          date: "2025-10-28",
          firstName: "Michelle",
          lastName: "Johnson",
          status: "terminated",
          clockIn: "08:50",
          teaOut: "10:55",
          teaIn: "11:10",
          lunchOut: "13:20",
          lunchIn: "14:00",
          clockOut: "17:35",
        },
        {
          id: 13,
          employeeId: "EMP001",
          date: "2025-10-29",
          firstName: "John",
          lastName: "Adams",
          status: "active",
          clockIn: "07:59",
          teaOut: "10:01",
          teaIn: "10:14",
          lunchOut: "12:26",
          lunchIn: "12:59",
          clockOut: "16:58",
        },
        {
          id: 14,
          employeeId: "EMP011",
          date: "2025-10-29",
          firstName: "Kevin",
          lastName: "Keller",
          status: "inactive",
          clockIn: "-",
          teaOut: "-",
          teaIn: "-",
          lunchOut: "-",
          lunchIn: "-",
          clockOut: "-",
        },
        {
          id: 15,
          employeeId: "EMP012",
          date: "2025-10-30",
          firstName: "Amanda",
          lastName: "Lewis",
          status: "active",
          clockIn: "08:00",
          teaOut: "10:05",
          teaIn: "10:20",
          lunchOut: "12:30",
          lunchIn: "13:05",
          clockOut: "17:00",
        },
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
    async onUpdateFilters(filters) {
      try {
        await this.$store.dispatch('apply_history_filter', filters)
        // optionally reset table page or other UI state here
      } catch (err) {
        console.error('Failed to apply history filters', err)
      }
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
    // load initial history
    this.$store.dispatch('fetch_history_info').catch(()=>{})
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

@media (max-width: 768px) {
  .row.mb-3.mt-4.mt-md-3.mt-sm-2 {
    margin-top: 1rem !important;
  }
}

@media (max-width: 576px) {
  .row.mb-3.mt-4.mt-md-3.mt-sm-2 {
    margin-top: 0.75rem !important;
  }
}
</style>