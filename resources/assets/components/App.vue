<template>
    <div>
        <v-toolbar>
            <v-toolbar-side-icon></v-toolbar-side-icon>
            <v-toolbar-title>Title</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn :to="{path:'/'}" flat>Главная</v-btn>
                <v-btn :to="{path:'/login'}" flat>Войти</v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-app>
            <router-view></router-view>
        </v-app>


    </div>


</template>

<script>
  export default {
    mounted() {
      console.log('Component mounted');
    }
  }
</script>
