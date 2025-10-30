<template>
<NavComp/>
<br><br><br><br><br><br><br>
<TimelogFilters/>
<TimeLog/>
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
};
</script>

<style scoped>

</style>