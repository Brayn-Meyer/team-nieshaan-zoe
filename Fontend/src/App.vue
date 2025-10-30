<template>
  <router-view/>
</template>
<script>
  export default {
    name: "App",

    async mounted() {
      await this.$store.dispatch("get_history_info");
    },
  };
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>
