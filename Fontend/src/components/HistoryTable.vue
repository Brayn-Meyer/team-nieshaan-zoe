<template>
  <div class="table-container">
    <!-- Mobile View -->
    <div class="d-md-none mobile-view">
      <div
        v-for="record in paginatedRecords"
        :key="record.id"
        class="card mb-3"
      >
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="employee-info">
              <h6 class="card-title mb-1">{{ getDisplayName(record) }}</h6>
              <small class="text-muted">{{ getEmployeeId(record) }}</small>
            </div>
          </div>
          <div class="mb-2">
            <small class="text-muted d-block">Date</small>
            <strong>{{ getDate(record) }}</strong>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <small class="text-muted d-block">Clock In</small>
              <strong>{{ getClockIn(record) || '-' }}</strong>
            </div>
            <div class="col-6">
              <small class="text-muted d-block">Clock Out</small>
              <strong>{{ getClockOut(record) || '-' }}</strong>
            </div>
          </div>
        </div>
      </div>
      <div v-if="recordsSource.length === 0" class="text-center text-muted py-4 no-records">
        No records found
      </div>
    </div>

    <!-- Tablet View -->
    <div class="d-none d-md-block d-xl-none tablet-view">
      <div class="table-responsive">
        <table class="tablet-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>ID</th>
              <th>Date</th>
              <th>Clock In</th>
              <th>Clock Out</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="record in paginatedRecords" :key="record.id">
              <td>{{ getDisplayName(record) }}</td>
              <td>{{ getEmployeeId(record) }}</td>
              <td>{{ getDate(record) }}</td>
              <td>{{ getClockIn(record) || '-' }}</td>
              <td>{{ getClockOut(record) || '-' }}</td>
            </tr>
            <tr v-if="recordsSource.length === 0">
              <td colspan="5" class="text-center text-muted no-records">No records found</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Desktop View -->
    <div class="d-none d-xl-block desktop-table-container">
      <div class="table-responsive">
        <table class="desktop-table">
          <thead>
            <tr>
              <th>Full Name</th>
              <th>Employee ID</th>
              <th>Date</th>
              <th>Clock In</th>
              <th>Clock Out</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="record in paginatedRecords" :key="record.id">
              <td>{{ getDisplayName(record) }}</td>
              <td>{{ getEmployeeId(record) }}</td>
              <td>{{ getDate(record) }}</td>
              <td>{{ getClockIn(record) || '-' }}</td>
              <td>{{ getClockOut(record) || '-' }}</td>
            </tr>
            <tr v-if="recordsSource.length === 0">
              <td colspan="5" class="text-center text-muted no-records">No records found</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <nav v-if="totalPages > 1" class="pagination-container">
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

    <!-- Records Info -->
    <div class="text-center text-muted small mt-2 records-info">
      Showing {{ paginatedRecords.length }} of {{ recordsSource.length }} records
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
      let maxVisible = 5;
      
      if (window.innerWidth < 576) {
        maxVisible = 3;
      } else if (window.innerWidth < 768) {
        maxVisible = 4;
      }
      
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
    getDisplayName(record) {
      return record.first_name + ' ' + record.last_name || record.firstName + ' ' + record.lastName || 'N/A';
    },

    getEmployeeId(record) {
      return record.employee_id || record.employeeId || 'N/A';
    },

    getDate(record) {
      if (record.work_date) {
        return this.formatDate(record.work_date);
      }
      return record.date || 'N/A';
    },

    getClockIn(record) {
      return record.work_clockin || record.clockIn;
    },

    getClockOut(record) {
      return record.work_clockout || record.clockOut;
    },

    sortBy(column) {
      if (this.sortColumn === column) {
        this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
      } else {
        this.sortColumn = column;
        this.sortDirection = "asc";
      }
    },

    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      if (window.innerWidth < 768) {
        return date.toLocaleDateString('en-US', {
          month: 'short',
          day: 'numeric'
        });
      } else if (window.innerWidth < 1024) {
        return date.toLocaleDateString('en-US', {
          month: 'short',
          day: 'numeric',
          year: '2-digit'
        });
      } else {
        return date.toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric'
        });
      }
    },

    changePage(page) {
      if (page >= 1 && page <= this.totalPages) this.currentPage = page;
    },

    updatePageSize() {
      if (window.innerWidth < 768) {
        this.pageSize = 5; // Mobile
      } else if (window.innerWidth < 1024) {
        this.pageSize = 8; // Tablet
      } else {
        this.pageSize = 10; // Desktop
      }
      this.currentPage = 1;
    },
  },

  async mounted() {
    this.updatePageSize();
    window.addEventListener("resize", this.updatePageSize);

    if (this.$store && this.$store.dispatch) {
      try {
        await this.$store.dispatch("fetch_history_info");
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
  width: 100%;
  max-width: 100%;
  margin: 0 auto;
}

/* Mobile Styles */
.mobile-view .card {
  border: 1px solid #e9ecef;
  border-radius: 0.5rem;
  margin-bottom: 1rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.mobile-view .card-body {
  padding: 1rem;
}

.mobile-view .card-title {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
  color: #2c3e50;
}

.mobile-view .employee-info small {
  font-size: 0.8rem;
  color: #6c757d;
}

.mobile-view .text-muted {
  font-size: 0.75rem;
  color: #6c757d !important;
}

.mobile-view strong {
  font-size: 0.85rem;
  color: #2c3e50;
  display: block;
  margin-top: 0.125rem;
}

/* Tablet Styles */
.tablet-view .table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.tablet-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 600px;
}

.tablet-table thead {
  background-color: #2EB28A;
  position: sticky;
  left: 0;
}

.tablet-table th {
  padding: 0.875rem 0.6rem;
  font-weight: 600;
  color: white;
  font-size: 0.82rem;
  white-space: nowrap;
  border-bottom: 2px solid #dee2e6;
}

.tablet-table td {
  padding: 0.75rem 0.6rem;
  border-bottom: 1px solid #dee2e6;
  font-size: 0.8rem;
  vertical-align: middle;
}

.tablet-table tbody tr:hover {
  background-color: #f8f9fa;
}

.tablet-table tbody tr:last-child td {
  border-bottom: none;
}

/* Desktop Styles */
.desktop-table-container {
  width: 100%;
  overflow-x: auto;
}

.desktop-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 700px;
}

.desktop-table thead {
  background-color: #2EB28A;
  position: sticky;
  left: 0;
}

.desktop-table th {
  padding: 1rem 0.75rem;
  font-weight: 600;
  color: white;
  font-size: 0.875rem;
  white-space: nowrap;
  border-bottom: 2px solid #dee2e6;
}

.desktop-table td {
  padding: 0.875rem 0.75rem;
  border-bottom: 1px solid #dee2e6;
  font-size: 0.85rem;
  vertical-align: middle;
}

.desktop-table tbody tr:hover {
  background-color: #f8f9fa;
}

.desktop-table tbody tr:last-child td {
  border-bottom: none;
}

/* Pagination Styles */
.pagination-container {
  width: 100%;
  padding: 0 1rem;
}

.page-link {
  cursor: pointer;
  min-width: 44px;
  text-align: center;
  padding: 0.5rem 0.75rem;
  border: 1px solid #dee2e6;
  color: #2EB28A;
  background: white;
  transition: all 0.2s ease;
}

.page-link:hover {
  background-color: #e9ecef;
  border-color: #dee2e6;
}

.page-item.active .page-link {
  background-color: #2EB28A;
  border-color: #2EB28A;
  color: white;
}

.page-item.disabled .page-link {
  color: #6c757d;
  background-color: #f8f9fa;
  border-color: #dee2e6;
}

.records-info {
  padding: 0.75rem 1rem;
  background-color: #f8f9fa;
  border-top: 1px solid #dee2e6;
  font-size: 0.8rem;
}

.no-records {
  padding: 2rem;
  color: #6c757d;
  font-style: italic;
}

/* Responsive Breakpoints */

/* Extra Small Devices (Phones) */
@media (max-width: 575.98px) {
  .table-container {
    border-radius: 0.5rem;
    margin: 0.25rem;
    width: calc(100% - 0.5rem);
  }
  
  .mobile-view .card {
    margin-bottom: 0.75rem;
  }
  
  .mobile-view .card-body {
    padding: 0.875rem;
  }
  
  .mobile-view .card-title {
    font-size: 0.95rem;
  }
  
  .mobile-view strong {
    font-size: 0.8rem;
  }
  
  .pagination-container {
    padding: 0 0.5rem;
  }
  
  .page-link {
    padding: 0.375rem 0.5rem;
    min-width: 40px;
    font-size: 0.8rem;
  }
  
  .records-info {
    font-size: 0.75rem;
    padding: 0.5rem;
  }
}

/* Small Devices (Phones) */
@media (min-width: 576px) and (max-width: 767.98px) {
  .table-container {
    border-radius: 0.75rem;
    margin: 0.5rem;
    width: calc(100% - 1rem);
  }
  
  .mobile-view .card-body {
    padding: 1rem;
  }
  
  .pagination-container {
    padding: 0 0.75rem;
  }
}

/* Medium Devices (Tablets) */
@media (min-width: 768px) and (max-width: 1199.98px) {
  .table-container {
    margin: 1rem;
    width: calc(100% - 2rem);
  }
  
  .tablet-table {
    min-width: 100%;
  }
  
  .tablet-table th {
    padding: 0.8rem 0.5rem;
    font-size: 0.8rem;
  }
  
  .tablet-table td {
    padding: 0.7rem 0.5rem;
    font-size: 0.78rem;
  }
  
  .pagination-container {
    padding: 0 0.75rem;
  }
}

/* Large Devices (Desktops) */
@media (min-width: 1200px) {
  .table-container {
    margin: 1.5rem auto;
    max-width: calc(100% - 3rem);
  }
  
  .desktop-table {
    min-width: 100%;
  }
  
  .desktop-table th {
    padding: 1.25rem 1rem;
    font-size: 0.9rem;
  }
  
  .desktop-table td {
    padding: 1rem;
    font-size: 0.9rem;
  }
}

/* Extra Large Devices */
@media (min-width: 1400px) {
  .table-container {
    max-width: 1320px;
  }
}

/* Landscape Orientation for Tablets */
@media (min-width: 768px) and (max-width: 1199.98px) and (orientation: landscape) {
  .tablet-table {
    min-width: 100%;
  }
  
  .tablet-table th {
    padding: 0.85rem 0.6rem;
  }
  
  .tablet-table td {
    padding: 0.75rem 0.6rem;
  }
}

/* Touch Device Optimizations */
@media (hover: none) and (pointer: coarse) {
  .page-link {
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .table-responsive {
    -webkit-overflow-scrolling: touch;
  }
  
  .mobile-view .card {
    margin-bottom: 0.875rem;
  }
}

/* High DPI Screens */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .table-container {
    box-shadow: 0 0.0625rem 0.125rem rgba(0, 0, 0, 0.1);
  }
  
  .mobile-view .card {
    box-shadow: 0 0.5px 1.5px rgba(0, 0, 0, 0.1);
  }
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
  .page-link {
    transition: none;
  }
  
  .desktop-table tbody tr,
  .tablet-table tbody tr {
    transition: none;
  }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
  .table-container {
    background: #2d3748;
    color: #e2e8f0;
  }
  
  .mobile-view .card {
    background: #2d3748;
    border-color: #4a5568;
  }
  
  .mobile-view .card-title,
  .mobile-view strong {
    color: #e2e8f0;
  }
  
  .desktop-table,
  .tablet-table {
    color: #e2e8f0;
  }
  
  .desktop-table td,
  .tablet-table td {
    border-color: #4a5568;
    color: #e2e8f0;
  }
  
  .desktop-table tbody tr:hover,
  .tablet-table tbody tr:hover {
    background-color: #4a5568;
  }
  
  .records-info {
    background-color: #2d3748;
    border-color: #4a5568;
    color: #a0aec0;
  }
}
</style>