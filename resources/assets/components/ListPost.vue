<template id="post-list">
  <div class="container">
    <div class="row">
      <h3>Posts</h3>
    </div>
    <div class="row">
      <div class="pull-right">
        <router-link class="btn btn-xs btn-primary" v-bind:to="{path: '/add-post'}">
          <span class="glyphicon glyphicon-plus"></span>
          Add new post
        </router-link>
        <br><br>
      </div>
    </div>


    <div class="row">
      <div class="col-1">#</div>
      <div class="col-2">Post Title</div>
      <div class="col-6">Post Body</div>
      <div class="col-3">Actions</div>
    </div>
    <div class="row post-row" v-for="(post, index) in listPosts">
      <div class="col-1">{{ post.id }}</div>
      <div class="col-2">{{ post.title }}</div>
      <div class="col-6">{{ post.message }}</div>
      <div class="col-3">
        <router-link class="btn btn-info btn-xs" v-bind:to="{name: 'ViewPost', params: {id: post.id}}">Show</router-link>
        <router-link class="btn btn-warning btn-xs" v-bind:to="{name: 'EditPost', params: {id: post.id}}">Edit</router-link>
        <router-link class="btn btn-danger btn-xs" v-bind:to="{name: 'DeletePost', params: {id: post.id}}">Delete</router-link>
      </div>
    </div>
    <pagination :current="currentPage" @page-changed="getPosts" :perPage="perPage" :total="total"></pagination>
  </div>
</template>

<script>
  import Pagination from './Pagination';

  export default {
    data() {
      return {posts: [],
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
      }
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
