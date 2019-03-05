<template>
  <v-container fluid>
    <v-layout>
      <v-flex>
        <sidebar></sidebar>
      </v-flex>
      <v-flex>
        <v-data-table
                :headers="headers"
                :items="listUsers"
                class="elevation-1"
        >
          <template slot="items" slot-scope="props">
            <td class="text-xs-left">{{ props.item.id }}</td>
            <td class="text-xs-left">{{ props.item.name }}</td>
            <td class="text-xs-left">{{ props.item.email }}</td>
            <td class="text-xs-left">{{ props.item.created_at }}</td>
          </template>
        </v-data-table>
      </v-flex>
    </v-layout>
  </v-container>
</template>


<script>
  import Sidebar from './Sidebar'
  export default {
    name: "Users",
    data() {
      return {
        headers: [
          {text: 'ID', value: 'id'},
          {text: 'Name', value: 'name'},
          {text: 'Email', value: 'email'},
          {text: 'Date', value: 'created_at'}
        ],
        users: []
      }
    },
    mounted: function () {
      Axios.get('/api/users').then((response) => {
        this.users = response.data.data;

      }).catch((error) => {
        if (error.response.statusCode == 401) {
          console.log(error.response.data);
          this.$router.push({name: 'Login'});
        } else {
          console.log(error.response.data);
        }
      });
    },
    methods: {

    },
    computed: {
      listUsers: function () {
        return this.users;
      }
    },
    components: {
      Sidebar
    }
  }
</script>

<style scoped>

</style>