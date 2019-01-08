<template id="view-post">
  <div class="container">
    <h3>{{ post.title }}</h3>
    <div>
      {{ post.message }}
    </div>
    <span class="glyphicon glyphicon-arrow-left"></span>
    <router-link v-bind:to="{ name: 'ListPost' }">Back to list</router-link>
  </div>
</template>

<script>
  export default {
    data() {
      return {post: {title: '', message: ''}}
    },
    created: function () {
      const url = '/api/posts/' + this.$route.params.id;
      Axios.get(url).then((response) => {
        this.post = response.data.data;
      });
    }
  }
</script>
