<template>
  <v-container fluid>
    <v-layout>
      <v-flex>
        <sidebar></sidebar>
      </v-flex>
      <v-flex>
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
            <td class="text-xs-left">{{ props.item.user_id }}</td>
            <td class="text-xs-left">{{ props.item.created_at }}</td>
          </template>
        </v-data-table>
      </v-flex>
    </v-layout>
  </v-container>
</template>


<script>
  import Sidebar from './Sidebar';
  import mixin from '../../mixins';

  export default {
    name: "Posts",
    data() {
      return {
        headers: [
          {text: 'ID', value: 'id'},
          {text: 'Title', value: 'title'},
          {text: 'Message', value: 'message'},
          {text: 'UserId', value: 'user_id'},
          {text: 'Date', value: 'created_at'}
        ],
        posts: [],
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
        this.getPosts(this.pagination.page, this.pagination.rowsPerPage).then((response) => {
          this.posts = response.items;
          this.pagination.totalItems = response.total;
          this.buildPosts();
          console.log(this.posts);
        }).catch((error) => {
          if (error.response.statusCode == 401) {
            console.log(error.response.data);
            this.$router.push({name: 'Login'});
          } else {
            console.log(error.response.data);
          }
        });
      },
      buildPosts: function () {
        this.posts = this.posts.map( (item) => {
          item.message = item.message.substring(0, 50);
          return item;
        })
      },
      updatePagination: function (event) {
        this.loadPosts();
        console.log(event);
      }
    },
    mixins: [mixin],
    components: {
      Sidebar
    }
  }
</script>

<style scoped>

</style>