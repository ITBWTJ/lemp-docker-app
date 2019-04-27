<template>
  <v-container fluid>
    <v-layout>
      <v-flex>
        <sidebar></sidebar>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import Sidebar from './Sidebar';

  export default {
    name: "admin",
    components: {
      Sidebar
    }
  }
</script>

<style scoped>

</style>