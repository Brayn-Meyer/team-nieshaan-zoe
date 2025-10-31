<template>
  <h1>Weekly Time Log</h1>
  <br>
  <div class="timelog-filters">
    <div class="filter-section">
      <div class="search-container">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search Employee..."
          class="search-input"
        />
      </div>
      <div class="week-container">
        <select
          v-model="selectedWeek"
          class="week-select"
        >
          <option value="">Select Week</option>
          <option v-for="week in weekOptions" :key="week.value" :value="week.value">
            {{ week.label }}
          </option>
        </select>
      </div>
      <div class="filter-buttons">
        <button
          class="filter-btn btn-red"
          :class="{ active: activeFilter === 'red' }"
          @click="setFilter('red')"
        >
          <span class="tooltip">Hours Owed</span>
        </button>
        <button
          class="filter-btn btn-green"
          :class="{ active: activeFilter === 'green' }"
          @click="setFilter('green')"
        >
          <span class="tooltip">Hours Worked</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TimelogFilters',
  data() {
    return {
      searchQuery: '',
      activeFilter: null,
      selectedWeek: '',
      weekOptions: [],
      refreshInterval: null
    }
  },
  methods: {
    setFilter(filterType) {
      this.activeFilter = this.activeFilter === filterType ? null : filterType;
      this.emitFilters();
    },
    generateWeekOptions() {
      const options = [];
      const today = new Date();
      
      for (let i = -4; i <= 8; i++) {
        const weekStart = new Date(today);
        weekStart.setDate(today.getDate() - today.getDay() + 1 + (i * 7)); 
        
        const weekEnd = new Date(weekStart);
        weekEnd.setDate(weekStart.getDate() + 4); 
        
        const value = weekStart.toISOString().split('T')[0];
        const startFormatted = this.formatDate(weekStart);
        const endFormatted = this.formatDate(weekEnd);
        
        let label = `Week of ${startFormatted} - ${endFormatted}`;
        
      
        if (i === 0) {
          label += ' (Current Week)';
        } else if (i === 1) {
          label += ' (Next Week)';
        } else if (i === -1) {
          label += ' (Last Week)';
        }
        
        options.push({ value, label, isCurrent: i === 0 });
      }
      
      this.weekOptions = options;
      
      const currentWeek = options.find(week => week.isCurrent);
      if (currentWeek && !this.selectedWeek) {
        this.selectedWeek = currentWeek.value;
      }
    },
    formatDate(date) {
      return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
      });
    },
    emitFilters() {
      this.$emit('filter-changed', {
        search: this.searchQuery,
        filter: this.activeFilter,
        week: this.selectedWeek
      });
    },
    setupWeeklyRefresh() {
      // Clear existing interval
      if (this.refreshInterval) {
        clearInterval(this.refreshInterval);
      }
      
      // Refresh weeks every day at midnight to ensure current week is always accurate
      this.refreshInterval = setInterval(() => {
        const now = new Date();
        if (now.getHours() === 0 && now.getMinutes() === 0) {
          this.generateWeekOptions();
        }
      }, 60000); // Check every minute
    },
    // Method to manually refresh weeks (can be called if needed)
    refreshWeeks() {
      this.generateWeekOptions();
    }
  },
  watch: {
    searchQuery() {
      this.emitFilters();
    },
    selectedWeek() {
      this.emitFilters();
    }
  },
  mounted() {
    this.generateWeekOptions();
    this.setupWeeklyRefresh();
  },
  beforeUnmount() {
    if (this.refreshInterval) {
      clearInterval(this.refreshInterval);
    }
  }
}
</script>

<style scoped>
h1{
    text-align: center;
    font-style: bold;
    font-family: 'Poppins', sans-serif;
    color: #333;
}

.timelog-filters {
  margin-bottom: 20px;
  display: flex;
  justify-content: center; 
}

.filter-section {
  display: flex;
  width: 80%;
  gap: 15px;
  align-items: center;
  padding: 15px;
  background-color: #F8F9FA;
  border-radius: 8px;
  border: 1px solid #E9ECEF;
}

.search-container {
  flex: 1;
  min-width: 0;
}

.search-input {
  width: 100%;
  padding: 10px 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.3s;
  height: 40px;
  font-family: 'Poppins', sans-serif;
  background-color: white;
  color: #333;
}

.search-input:focus {
  outline: none;
  border-color: #4A90E2;
  box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
}

.week-container {
  flex-shrink: 0;
  min-width: 280px;
}

.week-select {
  width: 100%;
  padding: 10px 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.3s;
  height: 40px;
  font-family: 'Poppins', sans-serif;
  background-color: white;
  color: #333;
  cursor: pointer;
}

.week-select:focus {
  outline: none;
  border-color: #4A90E2;
  box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
}

.filter-buttons {
  display: flex;
  gap: 12px;
  flex-shrink: 0;
}

.filter-btn {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 2px solid transparent;
  cursor: pointer;
  transition: all 0.3s;
  position: relative;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.filter-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.filter-btn.active {
  transform: translateY(0);
  box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.2);
  border: 2px solid #000000;
}

.btn-red {
  background-color: #E74C3C;
}

.btn-green {
  background-color: #2ECC71;
}

.tooltip {
  position: absolute;
  bottom: -40px;
  left: 50%;
  transform: translateX(-50%);
  background-color: #333;
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 14px;
  white-space: nowrap;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s;
  z-index: 10;
}

.tooltip::after {
  content: '';
  position: absolute;
  top: -5px;
  left: 50%;
  transform: translateX(-50%);
  border-width: 0 5px 5px 5px;
  border-style: solid;
  border-color: transparent transparent #333 transparent;
}

.filter-btn:hover .tooltip {
  opacity: 1;
  visibility: visible;
}

/* Mobile Responsive */
@media (max-width: 1024px) {
  .filter-section {
    width: 95%;
    gap: 12px;
  }
  
  .week-container {
    min-width: 250px;
  }
}

@media (max-width: 768px) {
  .filter-section {
    width: 95%;
    flex-direction: column;
    align-items: stretch;
    gap: 15px;
  }
  
  .search-container {
    width: 100%;
    min-width: auto;
  }
  
  .week-container {
    width: 100%;
    min-width: auto;
  }
  
  .week-select {
    width: 100%;
  }
  
  .filter-buttons {
    justify-content: center;
    width: 100%;
  }
}

@media (max-width: 480px) {
  .filter-section {
    width: 100%;
    margin: 0 10px;
    padding: 12px;
  }
  
  h1 {
    font-size: 1.5rem;
    padding: 0 10px;
  }
  
  .search-input,
  .week-select {
    font-size: 16px;
    height: 44px;
  }
  
  .week-container {
    min-width: auto;
  }
}
</style>