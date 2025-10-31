<template>
  <NavComp @theme-changed="handleThemeChange"/>
  <br><br><br><br><br><br><br>
  <TimelogFilters @filter-changed="handleFilterChange" :isDarkMode="isDarkMode"/>
  <TimeLog :filterData="filters" :isDarkMode="isDarkMode"/>
</template>

<script>
import TimeLog from '@/components/TimeLog.vue';
import NavComp from '@/components/NavComp.vue'
import TimelogFilters from '@/components/TimelogFilters.vue';

export default {
  components: {
    TimeLog,
    NavComp,
    TimelogFilters
  },
  data() {
    return {
      filters: {
        search: '',
        filter: null
      },
      isDarkMode: false
    }
  },
  methods: {
    handleFilterChange(filterData) {
      this.filters.search = filterData.search;
      this.filters.filter = filterData.filter;
    },
    handleThemeChange(isDarkMode) {
      this.isDarkMode = isDarkMode;
    }
  },
  mounted() {
    // Check for saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      this.isDarkMode = true;
    }
  }
};
</script>

<style scoped>
/* Main container dark mode */
:deep(.main-container) {
  transition: background-color 0.3s ease, color 0.3s ease;
}

:deep(.dark-mode .main-container) {
  background-color: #121212;
  color: #e0e0e0;
}

/* Filter component dark mode */
:deep(.filter-container) {
  transition: all 0.3s ease;
}

:deep(.dark-mode .filter-container) {
  background: #2d2d2d !important;
  color: #e0e0e0 !important;
  border-color: #404040 !important;
}

:deep(.dark-mode .filter-input) {
  background: #363636 !important;
  color: #e0e0e0 !important;
  border-color: #404040 !important;
}

:deep(.dark-mode .filter-input::placeholder) {
  color: #888 !important;
}

:deep(.dark-mode .filter-select) {
  background: #363636 !important;
  color: #e0e0e0 !important;
  border-color: #404040 !important;
}

/* Table component dark mode */
:deep(.dark-mode .time-log-table) {
  background: #2d2d2d !important;
  color: #e0e0e0 !important;
}

:deep(.dark-mode .table-header) {
  background: #363636 !important;
  color: #e0e0e0 !important;
  border-color: #404040 !important;
}

:deep(.dark-mode .table-row) {
  background: #2d2d2d !important;
  color: #e0e0e0 !important;
  border-color: #404040 !important;
}

:deep(.dark-mode .table-row:hover) {
  background: #363636 !important;
}

:deep(.dark-mode .table-cell) {
  color: #e0e0e0 !important;
  border-color: #404040 !important;
}
</style>