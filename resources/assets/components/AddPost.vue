<template id="add-post">
  <div>
    <h3>Add new Post</h3>

    <form v-on:submit.prevent = "createPost">
      <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" id="title" type="text" v-model="post.title" required>
      </div>

      <div class="form-group">
        <label for="body">Body</label>
        <textarea class="form-control" id="body" v-model="post.body" required ></textarea>
      </div>

      <button class="btn btn-primary">Create Post</button>
      <router-link class="btn btn-warning" v-bind:to="'/'">Cancel</router-link>
    </form>
  </div>
</template>

<script>
  export default {
    data() {
      return {post: {title: '', body: ''}};
    },
    methods: {
      createPost: function () {
        const url = '/post';
        Axios.post(url, this.post).then((response) => {
          this.$router.push({name: 'ListPost'});
        });
      }
    }
  }
</script>
