
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import BootstrapVue from 'bootstrap-vue'
import { Form, HasError, AlertError } from 'vform'
import swal from 'sweetalert2'
import VueGoodTablePlugin from 'vue-good-table';
import { objectToForm } from 'object-to-form';

import VueLightbox from 'vue-lightbox'

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
  
const options = {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '5px'
}
 
Vue.component("Lightbox",VueLightbox)
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);
Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);
Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

window.Form = Form;
window.swal = swal;
window.toast = toast;
window.objectToForm = objectToForm;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const routes = [
    { path: '/dashboard', component:  require('./components/ExampleComponent.vue') },
    { path: '/dashboard/profile', component:  require('./components/ProfileComponent.vue') },
    { path: '/dashboard/loaylty', component:  require('./components/CardsComponent.vue') },
    { path: '/dashboard/manage/users', component:  require('./components/UsersComponent.vue') },
    { path: '/dashboard/manage/customers', component:  require('./components/CustomersComponent.vue') },
    { path: '/dashboard/manage/rewards', component:  require('./components/RewardsComponent.vue') },
    { path: '/dashboard/orders', component:  require('./components/OrdersComponent.vue') },
    { path: '/dashboard/manage/passport', component:  require('./components/AdvanceComponent.vue') },
];

const router = new VueRouter({
    mode: 'history',
    routes,
});
window.router = router;

Vue.use(VueGoodTablePlugin);
Vue.use(BootstrapVue);
Vue.use(VueProgressBar, options);
Vue.use(VueRouter);




