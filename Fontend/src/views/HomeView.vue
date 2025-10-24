<template>
  <EmployeeTable/>
</template>

<script>
// @ is an alias to /src
import EmployeeTable from "@/components/EmployeeTable.vue";

export default {
  name: "HomeView",
  components: {
    EmployeeTable,
  },
};
</script>
