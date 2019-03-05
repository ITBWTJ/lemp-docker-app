<template>
  <div>
    <v-toolbar>
      <v-toolbar-side-icon></v-toolbar-side-icon>
      <v-toolbar-title>Title</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-items class="hidden-sm-and-down">
        <v-btn :to="{path:'/'}" flat>Главная</v-btn>
        <v-btn v-if="!user" :to="{path:'/login'}" flat>Войти</v-btn>
        <v-btn v-if="user" :to="{path:'/admin'}" flat>{{ user.first_name }}</v-btn>
      </v-toolbar-items>
    </v-toolbar>
    <v-app>
      <v-content>
        <router-view></router-view>
      </v-content>
    </v-app>


  </div>


</template>

<script>

  import mixin from '../mixins';

  export default {
    data: function () {
      return {
        user: null
      }
    },
    mixins: [mixin],
    created() {
      if (this.$store.state.token) {
        this.getUser(this.$store.state.token).then(data => {
          this.user = data;
        });
      }
    }
  }
</script>
