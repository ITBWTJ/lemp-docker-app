import Axios from 'axios';

export default {
  methods: {
    getUser: (token) => {
      return Axios.get('/api/users/me?token=' + token).then((response) => {
        return response.data.data;
      }).catch(() => {
        this.$store.commit('deleteToken');
        return null;
      });
    },
    sayHello: function () {
        console.log('Hello');
    },
    getUsers: (currentPage = 1, perPage = 5) => {
      const options = {
        perPage: perPage,
        currentPage: currentPage
      };
      return Axios.get('/api/users', {params: options}).then((response) => {
        return response.data.data;
      });
    },
    getPosts: (currentPage = 1, perPage = 5) => {
      const options = {
        perPage: perPage,
        currentPage: currentPage
      };
      return Axios.get('/api/posts', {params: options}).then((response) => {
        return response.data.data;
      });
    }
  }
}