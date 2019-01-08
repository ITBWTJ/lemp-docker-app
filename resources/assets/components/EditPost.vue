<template id="add-post">
  <div>
    <h3>Update Post #{{ post.id }}</h3>

    <form v-on:submit.prevent = "updatePost">
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
      return {post: {id: '', title: '', body: ''}};
    },
    created: function () {
      const url = '/post/' + this.$route.params.id;
      Axios.get(url).then((response) => {
        this.post = response.data.data;
      });
    },
    methods: {
      updatePost: function () {
        const url = '/post/' + this.post.id;
        Axios.put(url, this.post).then((response) => {
          this.$router.push({name: 'ListPost'});
        });
      }
    }
  }
</script>
