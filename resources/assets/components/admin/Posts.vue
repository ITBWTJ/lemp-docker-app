<template>
  <v-container fluid>
    <v-layout>
      <v-flex>
        <sidebar></sidebar>
      </v-flex>
      <v-flex>

        <v-btn @click="showCreateDialog" color="info">Create Post</v-btn>

        <v-data-table
                :headers="headers"
                :items="posts"
                :pagination.sync="pagination"
                :total-items="pagination.totalItems"
                @update:pagination="updatePagination"
                class="elevation-1"
        >
          <template slot="items" slot-scope="props">
            <td class="text-xs-left">{{ props.item.id }}</td>
            <td class="text-xs-left">{{ props.item.title }}</td>
            <td class="text-xs-left">{{ props.item.message }}</td>
            <td class="text-xs-left">{{ props.item.user }}</td>
            <td class="text-xs-left">{{ props.item.created_at }}</td>
            <td class="text-xs-left action-td">
              <v-icon @click="showEditDialog(props.item.id)" class="edit-icon">edit</v-icon>
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
                  @click="deleteEntity"
          >
            OK
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


    <v-dialog
            v-model="editDialog"
            max-width="490"
    >
      <v-card>
        <v-card-title class="headline">Edit post </v-card-title>
        <v-flex
                xs12
                md12
        >
          <v-text-field
                  class="m20"
                  v-model="editPost.title"
                  :counter="255"
                  label="Title"
                  name="title"
                  required
          ></v-text-field>
        </v-flex>
        <v-flex>
          <v-textarea
                  v-model="editPost.message"
                  class="m20"
                  name="message"
                  label="Message"
                  value="The Woodman set to work at once, and so sharp was his axe that the tree was soon chopped nearly through."
          ></v-textarea>
        </v-flex>
        <v-flex>
          <v-select
                  v-model="editPost.user_id"
                  :items="users"
                  :item-text="(el) => { return el.name; }"
                  :item-value="(el) => { return el.id; }"
                  class="m20"
                  box
                  label="Box style"
          ></v-select>
        </v-flex>
        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
                  color="green darken-1"
                  flat="flat"
                  @click="editDialog = false"
          >
            Cancel
          </v-btn>

          <v-btn
                  color="green darken-1"
                  flat="flat"
                  @click="updatePost"
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
        <v-card-title class="headline">Create post </v-card-title>
        <v-flex
                xs12
                md12
        >
          <v-text-field
                  class="m20"
                  v-model="newPost.title"
                  :counter="255"
                  label="Title"
                  name="title"
                  required
          ></v-text-field>
        </v-flex>
        <v-flex>
          <v-textarea
                  v-model="newPost.message"
                  class="m20"
                  name="message"
                  label="Message"
                  value="The Woodman set to work at once, and so sharp was his axe that the tree was soon chopped nearly through."
          ></v-textarea>
        </v-flex>
        <v-flex>
          <v-select
                  v-model="newPost.user_id"
                  :items="users"
                  :item-text="(el) => { return el.name; }"
                  :item-value="(el) => { return el.id; }"
                  class="m20"
                  box
                  label="Box style"
          ></v-select>
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
                  @click="createPost"
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
  import postMixin from '../../mixins/posts.js';
  import store from '../../store';

  export default {
    name: "Posts",
    data() {
      return {
        headers: [
          {text: 'ID', value: 'id'},
          {text: 'Title', value: 'title'},
          {text: 'Message', value: 'message'},
          {text: 'UserId', value: 'user_id'},
          {text: 'Date', value: 'created_at'},
          {text: 'action', value: 'action'}
        ],
        users: [],
        posts: [],
        dialog: false,
        editDialog: false,
        newDialog: false,
        editPost: {
          title: null,
          message: null,
          user_id: null
        },
        newPost: {
          title: null,
          message: null,
          user_id: null,
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

      loadPosts: function () {
        let posts = this.getPosts(this.pagination.page, this.pagination.rowsPerPage);
        let users = null;
        if (this.users.length < 1) {
          users = this.getUsers();
        }

        Promise.all([posts, users]).then(values => {
          console.log(values);

          let postReponse = values[0];
          let userResponse = values[1];

          if (userResponse) {
            this.users = userResponse.items;
          }

          this.posts = postReponse.items;
          this.pagination.totalItems = postReponse.total;
          this.buildPosts();
          this.$store.commit('setPosts', this.posts);
        }).catch(errors => {
          console.log('Promise All Error', errors);
        });
      },
      buildPosts: function () {
        this.posts = this.posts.map( (item) => {
          item.message = item.message.substring(0, 50);

          let user = this.users.find((obj) => {
            return obj.id === item.user_id;
          });

          item.user = user.name;

          return item;
        })
      },
      updatePagination: function (event) {
        this.loadPosts();
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
        const url = '/api/posts/' + this.deleteEntityId;
        Axios.delete(url).then((response) => {
          if (response.data.success) {

          }
        });
      },
      setPostPropertiesToForm: function () {

      },
      updatePost: function () {
        let params = {
            title: this.editPost.title,
            message: this.editPost.message,
            user_id: this.editPost.user_id,
            token: store.state.token,
        };

        let url = '/api/posts/' + this.editEntityId;
        Axios.put(url, params).then((response) => {
          console.log('Post Updated');
          this.updatePostUser(this.editEntityId, params.user_id);
          this.editDialog = false;
        }).catch((error) => {
            console.log(error.response.data.error);
        });

      },
      showCreateDialog: function () {
        if (this.users.length < 1) {
          this.getUsers().then((data) => {
            this.users = data.items;
          });
        }

        this.newDialog = true;
      },
      createPost: function () {
        let params = this.newPost;
        params.token = store.state.token;

        let url = '/api/posts';
        Axios.post(url, params).then((response) => {
          console.log('Post Created');
        }).catch((error) => {
          console.log(error.response.data.error);
        });

        this.newDialog = false;
        this.loadPosts();
      },
      updatePostUser: function (postId, userId) {

        this.posts = this.posts.map(post => {
          if (post.id === this.editEntityId) {
            let user = this.users.find(obj => {
              return obj.id === userId;
            });

            post.user = user.name;
          }

          return post;
        });

      }
    },
    mixins: [mixin, postMixin],
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