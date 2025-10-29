<template>
  <div class="table-container">
    <div class="d-md-none">
      <div
        v-for="record in paginatedRecords"
        :key="record.id"
        class="card mb-3"
      >
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <h6 class="card-title mb-1">{{ record.firstName }} {{ record.lastName }}</h6>
              <small class="text-muted">{{ record.employeeId }}</small>
            </div>
            <span
              class="badge"
              :class="{
                'bg-success': record.status === 'active',
                'bg-secondary': record.status === 'inactive',
                'bg-warning': record.status === 'on-leave',
                'bg-danger': record.status === 'terminated'
              }"
            >
              {{ record.status }}
            </span>
          </div>
          <div class="mb-2">
            <small class="text-muted d-block">Date</small>
            <strong>{{ record.date }}</strong>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <small class="text-muted d-block">Clock In</small>
              <strong>{{ record.clockIn || '-' }}</strong>
            </div>
            <div class="col-6">
              <small class="text-muted d-block">Clock Out</small>
              <strong>{{ record.clockOut || '-' }}</strong>
            </div>
            <div class="col-6">
              <small class="text-muted d-block">Tea Out</small>
              <strong>{{ record.teaOut || '-' }}</strong>
            </div>
            <div class="col-6">
              <small class="text-muted d-block">Tea In</small>
              <strong>{{ record.teaIn || '-' }}</strong>
            </div>
            <div class="col-6">
              <small class="text-muted d-block">Lunch Out</small>
              <strong>{{ record.lunchOut || '-' }}</strong>
            </div>
            <div class="col-6">
              <small class="text-muted d-block">Lunch In</small>
              <strong>{{ record.lunchIn || '-' }}</strong>
            </div>
          </div>
        </div>
      </div>
      <div v-if="recordsSource.length === 0" class="text-center text-muted py-4">
        No records found
      </div>
    </div>
    <div class="desktop-table-container d-none d-md-block">
      <table class="desktop-table">
        <thead>
          <tr>
            <th>Full Name</th>
            <th>Employee ID</th>
            <th>Status</th>
            <th>Date</th>
            <th>Clock In</th>
            <th>Tea Out</th>
            <th>Tea In</th>
            <th>Lunch Out</th>
            <th>Lunch In</th>
            <th>Clock Out</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in paginatedRecords" :key="record.id">
            <td>{{ record.firstName }} {{ record.lastName }}</td>
            <td>{{ record.employeeId }}</td>
            <td>
              <span class="status-badge" :class="record.status">
                {{ record.status }}
              </span>
            </td>
            <td>{{ record.date }}</td>
            <td>{{ record.clockIn || '-' }}</td>
            <td>{{ record.teaOut || '-' }}</td>
            <td>{{ record.teaIn || '-' }}</td>
            <td>{{ record.lunchOut || '-' }}</td>
            <td>{{ record.lunchIn || '-' }}</td>
            <td>{{ record.clockOut || '-' }}</td>
          </tr>
          <tr v-if="recordsSource.length === 0">
            <td colspan="10" class="text-center text-muted">No records found</td>
          </tr>
        </tbody>
      </table>
    </div>
    <nav v-if="totalPages > 1">
      <ul class="pagination justify-content-center mt-3 flex-wrap">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button class="page-link" @click="changePage(currentPage - 1)">
            <span class="d-none d-sm-inline">Previous</span>
            <span class="d-inline d-sm-none">←</span>
          </button>
        </li>
        <li
          class="page-item"
          v-for="page in visiblePages"
          :key="page"
          :class="{ active: currentPage === page }"
        >
          <button class="page-link" @click="changePage(page)">{{ page }}</button>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button class="page-link" @click="changePage(currentPage + 1)">
            <span class="d-none d-sm-inline">Next</span>
            <span class="d-inline d-sm-none">→</span>
          </button>
        </li>
      </ul>
    </nav>
    <div class="text-center text-muted small mt-2">
      Showing {{ paginatedRecords.length }} of {{ records.length }} records
      <span class="d-none d-md-inline">({{ pageSize }} per page)</span>
      <span class="d-md-none">(5 per page)</span>
    </div>
  </div>
</template>
<script>
  export default {
    name: "HistoryTable",
    props: {
      records: Array,
    },
    data() {
      return {
        sortColumn: null,
        sortDirection: "asc",
        currentPage: 1,
        pageSize: 10,
      };
    },
    computed: {
      // Use store data if available, otherwise fall back to prop
      recordsSource() {
        return this.$store?.state?.history_info?.length
          ? this.$store.state.history_info
          : this.records || [];
      },

      sortedRecords() {
        if (!this.sortColumn) return this.recordsSource;
        return [...this.recordsSource].sort((a, b) => {
          const valA = a[this.sortColumn]?.toString().toLowerCase() || "";
          const valB = b[this.sortColumn]?.toString().toLowerCase() || "";
          if (valA < valB) return this.sortDirection === "asc" ? -1 : 1;
          if (valA > valB) return this.sortDirection === "asc" ? 1 : -1;
          return 0;
        });
      },

      paginatedRecords() {
        const start = (this.currentPage - 1) * this.pageSize;
        return this.sortedRecords.slice(start, start + this.pageSize);
      },

      totalPages() {
        return Math.ceil(this.recordsSource.length / this.pageSize);
      },

      visiblePages() {
        const pages = [];
        const maxVisible = window.innerWidth < 576 ? 3 : 5;
        let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
        let end = Math.min(this.totalPages, start + maxVisible - 1);

        if (end - start + 1 < maxVisible) {
          start = Math.max(1, end - maxVisible + 1);
        }

        for (let i = start; i <= end; i++) {
          pages.push(i);
        }

        return pages;
      },
    },

    methods: {
      sortBy(column) {
        if (this.sortColumn === column) {
          this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
        } else {
          this.sortColumn = column;
          this.sortDirection = "asc";
        }
      },

      changePage(page) {
        if (page >= 1 && page <= this.totalPages) this.currentPage = page;
      },

      updatePageSize() {
        this.pageSize = window.innerWidth < 768 ? 5 : 10;
        this.currentPage = 1;
      },
    },

    async mounted() {
      this.updatePageSize();
      window.addEventListener("resize", this.updatePageSize);

      // Fetch store data safely
      if (this.$store && this.$store.dispatch) {
        try {
          await this.$store.dispatch("get_history_info");
        } catch (err) {
          console.warn("Failed to fetch history_info from store:", err);
        }
      }
    },

    beforeUnmount() {
      window.removeEventListener("resize", this.updatePageSize);
    },

    watch: {
      records() {
        this.currentPage = 1;
      },
    },
  };
</script>

<style scoped>
.table-container {
  background: white;
  border-radius: 0.5rem;
  overflow: hidden;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.desktop-table-container {
  overflow-x: auto;
}
.desktop-table {
  width: 100%;
  border-collapse: collapse;
}
.desktop-table thead {
  background-color: #2EB28A;
}
.desktop-table th {
  padding: 1.5rem;
  font-weight: 600;
  color: #FAFAFA;
}
.desktop-table td {
  padding: 0.75rem;
  border-bottom: 1px solid #DEE2E6;
}
.desktop-table tbody tr:hover {
  background-color: #F8F9FA;
}
.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-weight: 600;
  font-size: 0.75rem;
  text-transform: capitalize;
}
.status-badge.active {
  background-color: #2EB28A;
  color: white;
}
.status-badge.inactive {
  background-color: #6C757D;
  color: white;
}
.status-badge.on-leave {
  background-color: #FFC107;
  color: #212529;
}
.status-badge.terminated {
  background-color: #DC3545;
  color: white;
}
.page-link {
  cursor: pointer;
  min-width: 44px;
  text-align: center;
}
.card {
  border: 1px solid #DEE2E6;
  border-radius: 0.375rem;
}
.card-title {
  font-size: 0.9rem;
  font-weight: 600;
}
.badge {
  font-size: 0.7rem;
}
@media (max-width: 767.98px) {
  .table-container {
    border: none;
    background: transparent;
    box-shadow: none;
  }
}
@media (max-width: 360px) {
  .card-body {
    padding: 0.75rem;
  }
  .page-link {
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
  }
}
</style>