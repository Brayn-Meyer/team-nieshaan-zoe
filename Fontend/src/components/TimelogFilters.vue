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
      <div class="filter-buttons">
        <button
          class="filter-btn btn-red"
          :class="{ active: activeFilter === 'owed' }"
          @click="setFilter('owed')"
        >
          <span class="tooltip">Hours Owed</span>
        </button>
        <button
          class="filter-btn btn-green"
          :class="{ active: activeFilter === 'worked' }"
          @click="setFilter('worked')"
        >
          <span class="tooltip">Hours Worked</span>
        </button>
        <button
          class="filter-btn btn-yellow"
          :class="{ active: activeFilter === 'overtime' }"
          @click="setFilter('overtime')"
        >
          <span class="tooltip">Hours Overtime</span>
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
      activeFilter: null
    }
  },
  methods: {
    setFilter(filterType) {
      this.activeFilter = this.activeFilter === filterType ? null : filterType;
      this.$emit('filter-changed', {
        search: this.searchQuery,
        filter: this.activeFilter
      });
    }
  },
  watch: {
    searchQuery() {
      this.$emit('filter-changed', {
        search: this.searchQuery,
        filter: this.activeFilter
      });
    }
  }
}
</script>
<style scoped>
h1{
    text-align: center;
    font-style: bold;
    font-family: 'Poppins', sans-serif;
    
}
.timelog-filters {
  margin-bottom: 20px;
  display: flex;
  justify-content: center; /* Center the filter section */
}
.filter-section {
  display: flex;
  width: 50%; /* Set to 50% width */
  gap: 20px;
  align-items: center;
  padding: 15px;
  background-color: #F8F9FA;
  border-radius: 8px;
  border: 1px solid #E9ECEF;
}
.search-container {
  flex: 1; /* Take available space */
  min-width: 0;
}
.search-input {
  width: 100%;
  padding: 8px 15px;
  border: 1px solid #ddd;
  border-radius: 20px;
  font-size: 14px;
  transition: all 0.3s;
  height: 36px;
}
.search-input:focus {
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
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: none;
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
}
.btn-red {
  background-color: #E74C3C;
}
.btn-green {
  background-color: #2ECC71;
}
.btn-yellow {
  background-color: #F1C40F;
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
/* Responsive design */
@media (max-width: 768px) {
  .filter-section {
    width: 90%;
    flex-direction: column;
    align-items: stretch;
  }
  .search-container {
    width: 100%;
    min-width: auto;
  }
  .filter-buttons {
    justify-content: center;
  }
}
</style>
