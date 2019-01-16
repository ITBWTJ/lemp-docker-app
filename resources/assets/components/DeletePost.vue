<template id="delete-post">
  <div>
    <h3>Delete post {{ post.title }}</h3>
    <form v-on:submit.prevent="deletePost">
      <p>The action cannot be undone</p>
      <button class="btn btn-xs btn-warning" type="submit">Delete Post</button>
      <router-link class="btn btn-primary" v-bind:to="{name: 'ListPost'}">Back to Posts</router-link>
    </form>
  </div>
</template>


<script>
  export default {
    data: function () {
      return {post: {id: '', title:'', body: ''}}
    },
    created: function () {
      const url = '/api/posts/' + this.$route.params.id;
      Axios.get(url).then((response) => {
        this.post = response.data.data;
      });
    },
    methods: {
      deletePost: function () {
        const url = '/api/posts/' + this.$route.params.id;
        Axios.delete(url).then((response) => {
          if (response.data.success) {
            this.$router.push({name: 'ListPost'});
          }
        });
      }
    }
  }
</script>
