<template>
  <v-container fluid>
    <v-layout>
      <v-flex>
        <sidebar></sidebar>
      </v-flex>
      <v-flex>

        <v-btn @click="showCreateDialog" color="info">Create Email Sending</v-btn>

        <v-data-table
                :headers="headers"
                :items="items"
                :pagination.sync="pagination"
                :total-items="pagination.totalItems"
                @update:pagination="updatePagination"
                class="elevation-1"
        >
          <template slot="items" slot-scope="props">
            <td class="text-xs-left">{{ props.item.id }}</td>
            <td class="text-xs-left">{{ props.item.name }}</td>
            <td class="text-xs-left">{{ props.item.text }}</td>
            <td class="text-xs-left">{{ props.item.phone }}</td>
            <td class="text-xs-left">{{ props.item.status }}</td>
            <td class="text-xs-left">{{ props.item.created_at }}</td>
            <td class="text-xs-left action-td">
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
        <v-card-title class="headline">Delete Sms?</v-card-title>

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
                  @click="deleteEntity"
          >
            OK
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


    <v-dialog
            v-model="newDialog"
            max-width="490"
    >
      <v-card>
        <v-card-title class="headline">Create SMS </v-card-title>
        <v-flex
                xs12
                md12
        >
          <v-text-field
                  class="m20"
                  v-model="newItem.name"
                  :counter="255"
                  label="Title"
                  name="name"
                  :error-messages="errors.name"
                  required
          ></v-text-field>
        </v-flex>
        <v-flex>
          <v-textarea
                  v-model="newItem.text"
                  class="m20"
                  name="text"
                  label="Message"
                  :error-messages="errors.text"
                  value="The Woodman set to work at once, and so sharp was his axe that the tree was soon chopped nearly through."
          ></v-textarea>
        </v-flex>
        <v-flex>
          <v-textarea
                  v-model="newItem.phone"
                  class="m20"
                  name="phone"
                  label="Number"
                  :error-messages="errors.phone"
                  value="The Woodman set to work at once, and so sharp was his axe that the tree was soon chopped nearly through."
          ></v-textarea>
        </v-flex>
        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
                  color="green darken-1"
                  flat="flat"
                  @click="newDialog = false"
          >
            Cancel
          </v-btn>

          <v-btn
                  color="green darken-1"
                  flat="flat"
                  @click="createItem"
          >
            OK
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>


<script>
  import Sidebar from './Sidebar';
  import mixin from '../../mixins';
  import store from '../../store';

  export default {
    name: "Sms",
    data() {
      return {
        headers: [
          {text: 'ID', value: 'id'},
          {text: 'Name', value: 'name'},
          {text: 'Text', value: 'text'},
          {text: 'Phone', value: 'phone'},
          {text: 'Status', value: 'status'},
          {text: 'Date', value: 'created_at'},
          {text: 'action', value: 'action'}
        ],
        users: [],
        items: [],
        errors: {
          name: [],
          text: [],
          phone: []
        },
        dialog: false,
        newDialog: false,
        newItem: {
          name: '',
          phone: '',
          text: ''
        },
        deleteEntityId: null,
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

    },
    methods: {

      loadItems: function () {
        let promise = this.getItems(this.pagination.page, this.pagination.rowsPerPage);

        promise.then((data) => {
            this.items = data.items;
            console.log('loadItems', this.items, data);
            this.pagination.totalItems = data.total;
            this.$store.commit('setSms', this.items);
        });

      },
      getItems: (currentPage = 1, perPage = 5) => {
        const options = {
            perPage: perPage,
            currentPage: currentPage,
            token: store.state.token
        };
        return Axios.get('/api/sms', {params: options}).then((response) => {
            return response.data.data;
        });
      },

      updatePagination: function (event) {
        this.loadItems();
        console.log(event);
      },
      showDialog: function (id) {
        console.log('Show');
        this.deleteEntityId = id;
        this.dialog = true;
      },
      showEditDialog: function (id) {
        console.log('Edit');
        this.editEntityId = id;
        this.editPost = this.findPostById(id);
        this.editDialog = true;

        if (this.users.length < 1) {
          this.getUsers().then((data) => {
            this.users = data.items;
          });
        }
      },
      deleteEntity: function () {
        this.dialog = false;
        const url = '/api/sms/' + this.deleteEntityId + '?token=' + store.state.token;
        Axios.delete(url).then((response) => {
          console.log('delete entity', response.data);
          this.loadItems();
        }).catch(error => {
            console.log(error);
        });
      },
      setPostPropertiesToForm: function () {

      },
      showCreateDialog: function () {
        this.resetErrors();
        this.newDialog = true;
      },
      createItem: function () {
        this.resetErrors();

        let params = this.newItem;
        params.token = store.state.token;

        let url = '/api/sms';
        Axios.post(url, params).then((response) => {
          console.log('sms Created');

          this.newDialog = false;
          this.loadItems();
        }).catch((error) => {
          console.log(error.response.data.error);
          this.emailErrors = this.passwordErrors = [];
          const errors = error.response.data.error;

          for (let key in error.response.data.error) {
            if (key === 'name') this.errors.name = Object.values(errors[key]);
            if (key === 'text') this.errors.text = Object.values(errors[key]);
            if (key === 'phone') this.errors.phone = Object.values(errors[key]);
          }
        });
      },
      resetErrors: function () {
        this.errors = {
          name: [],
          text: [],
          phone: []
        }
      }

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
  .m20 {
                                                                                            margin-left: 20px;
                                                                                            margin-right: 20px;
  }
</style>
