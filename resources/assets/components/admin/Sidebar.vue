<template>
  <v-navigation-drawer permanent>
    <v-toolbar flat>
      <v-list>
        <v-list-tile>
          <v-list-tile-title class="title">
            Application
          </v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-toolbar>

    <v-divider></v-divider>

    <v-list dense class="pt-0">
      <v-list-tile
              v-for="item in items"
              :key="item.title"
              @click=""
      >

          <v-list-tile-action>
              <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>

          <v-list-tile-content>
            <router-link :to="{path: item.url}">
              <v-list-tile-title>{{ item.title }}</v-list-tile-title>
            </router-link>
          </v-list-tile-content>

      </v-list-tile>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  export default {
    name: "Sidebar",
    data() {
      return {
        items: [
          {
            title: 'Users',
            icon: 'people',
            url: '/admin/users'
          },
          {
            title: 'Posts',
            icon: 'library_books',
            url: '/admin/posts'
          },
          {
            title: 'Sms',
            icon: 'library_books',
            url: '/admin/sms'
          }
        ]
      }
    }
  }
</script>

<style scoped>

</style>
