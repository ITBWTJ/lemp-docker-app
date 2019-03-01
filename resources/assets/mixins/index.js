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
        }
    }
}