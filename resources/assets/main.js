import Vue from 'vue';
import VueAxios from 'vue-axios';
import Axios from 'axios';
import VueRouter from 'vue-router';

window.Vue = Vue;

window.VueRouter = VueRouter;

window.VueAxios = VueAxios;

window.Axios = Axios;

import App from './components/App.vue';

// show the list post templates
const Listpost = Vue.component('ListPost', require('./components/ListPost.vue').default);

// add post template
const Addpost = Vue.component('AddPost', require('./components/AddPost.vue').default);

// edite post template
const Editpost = Vue.component('EditPost', require('./components/EditPost.vue').default);

// delete post template
const Deletepost = Vue.component('DeletePost', require('./components/DeletePost.vue').default);

// view single post template
const Viewpost = Vue.component('ViewPost', require('./components/ViewPost.vue').default);

// registering Modules
Vue.use(VueRouter,VueAxios, Axios);

const routes = [
  {
    name: 'ListPost',
    path: '/',
    component: Listpost
  },
  {
    name: 'AddPost',
    path: '/add-post',
    component: Addpost
  },
  {
    name: 'EditPost',
    path: '/edit/:id',
    component: Editpost
  },
  {
    name: 'DeletePost',
    path: '/post-delete',
    component: Deletepost
  },
  {
    name: 'ViewPost',
    path: '/view/:id',
    component: Viewpost
  }
];

const router = new VueRouter({ mode: 'history', routes: routes});

new Vue({
  router,
  render: h => h(App)
}).$mount('#app');
