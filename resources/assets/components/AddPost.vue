<template id="add-post">
  <div>
    <h3>Add new Post</h3>

    <form v-on:submit.prevent = "createPost">
      <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" id="title" type="text" v-model="post.title" required>
      </div>

      <div class="form-group">
        <label for="body">Message</label>
        <textarea class="form-control" id="body" v-model="post.message" required ></textarea>
      </div>

      <div class="form-group">
        <select name="user_id" id="users" v-model="post.user_id" class="form-control">
          <option v-for="(user, index) in users" :value="user.id">{{ user.first_name + ' ' + user.last_name }}</option>
        </select>
      </div>

      <button class="btn btn-primary">Create Post</button>
      <router-link class="btn btn-warning" v-bind:to="'/'">Cancel</router-link>
    </form>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        post: {title: '', message: '', user_id: ''},
        users: []
      };
    },
    created: function () {
      Axios.get('/api/users').then((response) => {
          if (response.data.success) {
            this.users = response.data.data;
          }
      });
    },
    methods: {
      createPost: function () {
        Axios.post('/api/posts', this.post).then((response) => {
          if (response.data.success) {
            this.$router.push({name: 'ListPost'});
          }
        });
      }
    }
  }
</script>
