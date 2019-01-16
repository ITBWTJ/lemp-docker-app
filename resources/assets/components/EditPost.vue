<template id="add-post">
  <div>
    <h3>Update Post #{{ post.id }}</h3>

    <form v-on:submit.prevent = "updatePost">
      <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" id="title" type="text" v-model="post.title" required>
      </div>

      <div class="form-group">
        <label for="body">Message</label>
        <textarea class="form-control" id="body" v-model="post.message" required ></textarea>
      </div>
      <div class="form-group">
        <label for="users-select">Author</label>
        <select class="form-control" id="users-select" v-model="post.user_id" name="user_id">
          <option v-for="(user, index) in users" :value="user.id">{{ user.first_name + ' ' + user.last_name }}</option>
        </select>
      </div>

      <button class="btn btn-primary">Update Post</button>
      <router-link class="btn btn-warning" v-bind:to="'/'">Cancel</router-link>


    </form>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        post: {id: '', title: '', message: '', user_id: ''},
        users: []
      };
    },
    created: function () {
      const url = '/api/posts/' + this.$route.params.id;
      Axios.get(url).then((response) => {
        this.post = response.data.data;
      });

      const usersUrl = '/api/users';
      Axios.get(usersUrl).then((response) => {
        this.users = response.data.data;
      });
    },
    methods: {
      updatePost: function () {
        const url = '/api/posts/' + this.post.id;
        Axios.put(url, this.post).then((response) => {
          if (response.data.success) {
            this.$router.push({name: 'ListPost'});
          }

        });
      }
    }
  }
</script>
