<template id="post-list">
  <v-container>
    <div class="row">
      <h3>Posts</h3>
    </div>
    <div class="row">
      <div class="pull-right">
        <v-btn :to="{path: '/add-post'}" color="info" >Add new post</v-btn>
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
        <v-btn :to="{name: 'ViewPost', params: {id: post.id}}" color="info" small>Show</v-btn>
        <v-btn :to="{name: 'EditPost', params: {id: post.id}}" color="warning" small>Edit</v-btn>
        <v-btn :to="{name: 'DeletePost', params: {id: post.id}}"  color="error" small>Delete</v-btn>
      </div>
    </div>
    <!--<pagination :current="currentPage" @page-changed="getPosts" :perPage="perPage" :total="total"></pagination>-->
    <v-pagination  :length="totalPage" v-model="currentPage" :total-visible="5" @input="getPosts" :value="1"></v-pagination>
  </v-container>
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
