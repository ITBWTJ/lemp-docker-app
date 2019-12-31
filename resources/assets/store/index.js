import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    token: localStorage.getItem('token') || null,
    user: null,
    users: [],
    posts: [],
    sms: [],
  },
  actions: {
    setToken: (token) => {
      this.state.token = token;
      localStorage.setItem('token', token);
    },
    setPosts: posts => {
      this.state.posts = posts;
    },
  },
  mutations: {
    setToken: (state, token) => {
      state.token = token;
      localStorage.setItem('token', token);
    },
    deleteToken: (state) => {
      state.token = null;
      state.user = null;
      localStorage.setItem('token', null);
    },
    setUser: (state, user) => {
      state.user = user;
    },
    getUsers: (state) => {
      Axios.get('/api/users')
    },
    setPosts: (state, posts) => {
      state.posts = posts;
    },
    setSms: (state, items) => {
      state.sms = items;
    },
  }
})
