import Vue from 'vue';
import Router from 'vue-router';
import ListPost from '../components/ListPost.vue';
import AddPost from '../components/AddPost';
import EditPost from '../components/EditPost';
import DeletePost from '../components/DeletePost';
import ViewPost from '../components/ViewPost';
import Login from '../components/Login';
import Admin from '../components/admin/Admin';
import AdminUsers from '../components/admin/Users';
import AdminPosts from '../components/admin/Posts';
import EmailSending from "../components/admin/EmailSending";
import Sms from "../components/admin/Sms";

Vue.use(Router);

export default new Router({
        mode: 'history',
        routes: [
            {
                name: 'ListPost',
                path: '/',
                component: Login
            },
            {
                name: 'AddPost',
                path: '/add-post',
                component: AddPost
            },
            {
                name: 'EditPost',
                path: '/edit/:id',
                component: EditPost
            },
            {
                name: 'DeletePost',
                path: '/post-delete/:id',
                component: DeletePost
            },
            {
                name: 'ViewPost',
                path: '/view/:id',
                component: ViewPost
            },
            {
                name: 'Login',
                path: '/login',
                component: Login
            },
            {
                name: 'Admin',
                path: '/admin',
                component: Admin
            },
            {
                name: 'Admin.Users',
                path: '/admin/users',
                component: AdminUsers
            },
            {
                name: 'Admin.Posts',
                path: '/admin/posts',
                component: AdminPosts
            },
            {
                name: 'Admin.Posts.Edit',
                path: '/admin/posts/:id',
                component: AdminPosts
            },
            {
                name: 'Admin.Users.Edit',
                path: '/admin/users/:id',
                component: AdminUsers
            },
            {
                name: 'Admin.EmailSending',
                path: '/admin/email-sending',
                component: EmailSending
            },
            {
              name: 'Admin.Sms',
              path: '/admin/sms',
              component: Sms
            }

        ]});
