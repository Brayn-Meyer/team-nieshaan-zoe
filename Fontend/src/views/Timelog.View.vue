<template>
  <NavComp />
  <br><br><br><br><br><br><br>
  <TimelogFilters @filter-changed="handleFilterChange" />
  <TimeLog :filterData="filters" />
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
        filter: null,
        week: null
      }
    }
  },
  methods: {
    handleFilterChange(filterData) {
      this.filters.search = filterData.search;
      this.filters.filter = filterData.filter;
      this.filters.week = filterData.week;
    }
  }
};
</script>

<style scoped>

</style>