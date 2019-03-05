import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    token: localStorage.getItem('token') || null,
    user: null
  },
  actions: {
    setToken: (token) => {
      this.state.token = token;
      localStorage.setItem('token', token);
    }
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
    }
  }
})