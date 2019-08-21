<template>
  <v-container fluid>
    <v-layout>
      <v-flex>
        <sidebar></sidebar>
      </v-flex>
      <v-flex>
        <v-data-table
                :headers="headers"
                :items="users"
                :pagination.sync="pagination"
                @update:pagination="updatePagination"
                class="elevation-1"
        >
          <template slot="items" slot-scope="props">
            <td class="text-xs-left">{{ props.item.id }}</td>
            <td class="text-xs-left">{{ props.item.name }}</td>
            <td class="text-xs-left">{{ props.item.email }}</td>
            <td class="text-xs-left">{{ props.item.created_at }}</td>
            <td class="text-xs-left action-td">
              <router-link :to="{name: 'Admin.Users.Edit', params: {id: props.item.id}}"><v-icon class="edit-icon">create</v-icon></router-link>
              <v-icon @click="showDialog(props.item.id)" class="delete-icon">clear</v-icon>
            </td>
          </template>
        </v-data-table>
      </v-flex>
    </v-layout>
    <v-dialog
            v-model="dialog"
            max-width="290"
    >
      <v-card>
        <v-card-title class="headline">Delete User?</v-card-title>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
                  color="green darken-1"
                  flat="flat"
                  @click="dialog = false"
          >
            Cancel
          </v-btn>

          <v-btn
                  color="green darken-1"
                  flat="flat"
                  @click="deleteUser"
          >
            OK
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
            v-model="editUser"
            max-width="290"
    >
      <v-card>
        <v-card-title class="headline">Edit user</v-card-title>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
                  color="green darken-1"
                  flat="flat"
                  @click="dialog = false"
          >
            Cancel
          </v-btn>

          <v-btn
                  color="green darken-1"
                  flat="flat"
                  @click="deleteUser"
          >
            OK
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>


<script>
  import Sidebar from './Sidebar'
  import mixin from '../../mixins';

  export default {
    name: "Users",
    data() {
      return {
        headers: [
          {text: 'ID', value: 'id'},
          {text: 'Name', value: 'name'},
          {text: 'Email', value: 'email'},
          {text: 'Date', value: 'created_at'},
          {text: 'Action', value: 'action'}
        ],
        dialog: false,
        editUser: false,
        deleteUserId: null,
        users: [],
        pagination: {
          descending: true,
          page: 1,
          rowsPerPage: 5,
          sortBy: 'id',
          totalItems: 0
        }
      }
    },
    mounted: function () {
      // Axios.get('/api/users').then((response) => {
      //   this.users = response.data.data;
      //
      // }).catch((error) => {
      //   if (error.response.statusCode == 401) {
      //     console.log(error.response.data);
      //     this.$router.push({name: 'Login'});
      //   } else {
      //     console.log(error.response.data);
      //   }
      // });
    },
    methods: {
      loadUsers: function () {
        this.getUsers(this.pagination.page, this.pagination.rowsPerPage).then((response) => {
          this.users = response.items;
          this.pagination.totalItems = response.total;

        }).catch((error) => {
          if (error.response.statusCode == 401) {
            console.log(error.response.data);
            this.$router.push({name: 'Login'});
          } else {
            console.log(error.response.data);
          }
        });
      },
      updatePagination: function (event) {
        this.loadUsers();
        console.log(event);
      },
      showDialog: function (id) {
        console.log('Show');
        this.deleteUserId = id;
        this.dialog = true;
      },
      deleteUser: function () {
        this.dialog = false;
        const url = '/api/users/' + this.deleteUserId;
        Axios.delete(url).then((response) => {
          if (response.data.success) {

          }
        });
      }
    },
    computed: {
      // listUsers: function () {
      //   return this.users;
      // }
    },
    mixins: [mixin],
    components: {
      Sidebar
    }
  }
</script>

<style scoped>
  .action-td {
    font-size: 1.5rem;
  }
  .action-td a {
    text-decoration: none;
  }
</style>