<template>
  <div class="card p-3 shadow-sm mx-auto">
    <div class="row g-2 align-items-end justify-content-center text-center">
      <div class="col-12 col-sm-4 col-md-3">
        <label class="form-label small fw-bold">Select Date</label>
        <input type="date" class="form-control form-control-sm" v-model="localFilters.date" />
      </div>
      <div class="col-12 col-sm-4 col-md-4">
        <label class="form-label small fw-bold">Name (First or Last)</label>
        <input
          type="text"
          class="form-control form-control-sm"
          placeholder="Enter name"
          v-model="localFilters.name"
        />
      </div>
      <!-- <div class="col-12 col-sm-4 col-md-2">
        <label class="form-label small fw-bold">Status</label>
        <select class="form-select form-select-sm" v-model="localFilters.status">
          <option value="">All</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
          <option value="on-leave">On Leave</option>
          <option value="terminated">Terminated</option>
        </select>
      </div> -->
      <div class="col-12 col-sm-12 col-md-3">
        <button class="btn apply btn-sm w-100" @click="applyFilters">
          <i class="bi bi-funnel me-1"></i>Apply Filters
        </button>
      </div>
    </div>
    <div class="row mt-3" v-if="hasActiveFilters">
      <div class="col-12">
        <div class="d-flex flex-wrap align-items-center gap-2">
          <small class="text-muted fw-bold">Active Filters:</small>
          <span
            v-for="(value, key) in activeFilters"
            :key="key"
            class="badge bg-primary d-flex align-items-center"
          >
            {{ getFilterLabel(key) }}: {{ value }}
            <button
              @click="clearFilter(key)"
              class="btn-close btn-close-white ms-1"
              style="font-size: 0.6rem;"
            ></button>
          </span>
          <button
            @click="clearAllFilters"
            class="btn btn-outline-secondary btn-sm"
          >
            Clear All
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "HistoryFilters",
  props: {
    filters: {
      type: Object,
      default: () => ({ date: '', name: '', status: '', employeeId: '' }),
    },
  },
  data() {
    return {
      localFilters: { ...this.filters },
    };
  },
  computed: {
    hasActiveFilters() {
      return Object.values(this.localFilters).some(value => value !== '');
    },
    activeFilters() {
      const active = {};
      Object.entries(this.localFilters).forEach(([key, value]) => {
        if (value !== '') {
          active[key] = value;
        }
      });
      return active;
    }
  },
  watch: {
    filters: {
      handler(newFilters) {
        this.localFilters = { ...newFilters };
      },
      deep: true,
      immediate: true,
    },
  },
  methods: {
    applyFilters() {
      this.$emit("update-filters", this.localFilters);
    },
    clearFilter(filterKey) {
      this.localFilters[filterKey] = '';
      this.applyFilters();
    },
    clearAllFilters() {
      this.localFilters = { date: '', name: '', status: '', employeeId: '' };
      this.applyFilters();
    },
    getFilterLabel(key) {
      const labels = {
        date: 'Date',
        name: 'Name',
        status: 'Status',
        employeeId: 'Employee ID'
      };
      return labels[key] || key;
    }
  },
};
</script>
<style scoped>
.card {
  border-radius: 0.5rem;
  border: 1px solid #DEE2E6;
  width: 50%;
  background-color: #EBEBEB;
}
.form-label {
  margin-bottom: 0.25rem;
}
.btn {
  white-space: nowrap;
}
.badge {
  font-size: 0.75rem;
  padding: 0.375rem 0.5rem;
  background-color: #2EB28A !important;
}
.apply{
  color: white;
  background-color: #2EB28A !important;
}
/* Improved responsive behavior */
@media (max-width: 1200px) {
  .card {
    width: 75%;
  }
}
@media (max-width: 768px) {
  .card {
    width: 90%;
  }
}
@media (max-width: 576px) {
  .card {
    width: 100%;
    padding: 1rem !important;
  }
  .btn {
    min-width: 120px;
  }
}
</style>