require('./bootstrap');

window.Vue = require('vue');
window.VueSelect = require('vue-select');

Vue.component('v-select', VueSelect.VueSelect);
Vue.component('register-component', require('./components/RegisterComponent.vue'));
Vue.component('login-component', require('./components/LoginComponent.vue'));
Vue.component('list-cameras-component', require('./components/ListCameraComponent.vue'));
Vue.component('create-camera-component', require('./components/CreateCameraComponent.vue'));

const register = new Vue({
    el: '#vue',
});
