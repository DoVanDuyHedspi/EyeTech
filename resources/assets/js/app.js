require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import VueAuth from '@websanova/vue-auth';
import axios from 'axios';
import App from  './components/App.vue';
import Home from './components/Home.vue';
import Login from './components/auth/Login.vue';
import Register from './components/auth/Register.vue';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

axios.create({
    baseURL: 'http://localhost:8888/eyetech/api/v1',
    registerURL: 'http://localhost:8888/eyetech/api/v1/users/regis ter',
});

const routes = [
    { path: '/home', name: 'home', component: Home},
    { path: '/login', name: 'login', component: Login},
    { path: '/register', name: 'register', component: Register},
];

const router = new VueRouter({
   routes: routes
});

Vue.router = router;
Vue.use(VueAuth, {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});

const app = new Vue({
    router: router,
    el: '#app',
    components: {
        App,
        Home,
        Login,
        Register,
    }
});
