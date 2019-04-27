<template id="post-list">
  <v-container grid-list-md>
    <v-layout row>
      <v-flex xs2>
        <h3>Posts</h3>
      </v-flex>
    </v-layout>
    <v-layout>
      <v-flex xs2>
        <v-btn :to="{path: '/add-post'}" color="info" >Add new post</v-btn>
      </v-flex>
    </v-layout>

      <v-layout row wrap>
        <v-flex xs1>#</v-flex>
        <v-flex xs3>Post Title</v-flex>
        <v-flex xs4>Post Body</v-flex>
        <v-flex xs4>Actions</v-flex>
      </v-layout>
        <v-layout row wrap v-for="(post, index) in listPosts">
        <v-flex xs1>{{ post.id }}</v-flex>
        <v-flex xs3>{{ post.title }}</v-flex>
        <v-flex xs4>{{ post.message }}</v-flex>
        <v-flex xs4>
          <v-btn :to="{name: 'ViewPost', params: {id: post.id}}" color="info" small>Show</v-btn>
          <v-btn :to="{name: 'EditPost', params: {id: post.id}}" color="warning" small>Edit</v-btn>
          <v-btn :to="{name: 'DeletePost', params: {id: post.id}}"  color="error" small>Delete</v-btn>
        </v-flex>
      </v-layout>
      <!--<pagination :current="currentPage" @page-changed="getPosts" :perPage="perPage" :total="total"></pagination>-->
      <v-pagination  :length="totalPage" v-model="currentPage" :total-visible="5" @input="getPosts" :value="1"></v-pagination>

  </v-container>
</template>

<script>
  import Pagination from './Pagination';

  export default {
    data() {
      return {
        posts: [],
        total: 0,
        perPage: 3,
        currentPage: 1
      };
    },
    created: function () {
      this.getPosts();
    },
    computed: {
      listPosts: function () {
        if (this.posts.length) {
          return this.posts;
        }
      },
      totalPage: function () {
        return Math.ceil(this.total / this.perPage);
      },
    },
    methods: {
      getPosts: function (currentPage) {
        this.currentPage = currentPage;
        const options = {
          perPage: this.perPage,
          currentPage: this.currentPage
        };
        Axios.get('/api/posts', {params: options}).then((response) => {
          this.posts = response.data.data.items;
          this.total = response.data.data.total;
        });
      }
    },
    components: {
      Pagination
    }
  }
</script>
<style>
  .post-row {
    margin: 20px 0;
  }
  .row h3 {
    margin: 30px auto;
  }
</style>
