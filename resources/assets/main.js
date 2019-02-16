import Vue from 'vue';
import VueAxios from 'vue-axios';
import Axios from 'axios';
import './plugins/vuetify';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'
import router from './routes'
import store from './store';

window.Vue = Vue;

window.VueAxios = VueAxios;

window.Axios = Axios;

// registering Modules
Vue.use(Vuetify, VueAxios, Axios);


import App from './components/App.vue';



Axios.defaults.baseURL = 'http://172.17.0.1';

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
