require('./bootstrap');
import VueRouter from 'vue-router';
import UrlStats from './components/dashboard/UrlStats.vue';
import store from './store/store';
import Vuex from 'vuex';
import Vue from 'vue';
import remaining from './directives/RemainingHeight'

window.Vue = Vue;

Vue.use(Vuex);
Vue.use(VueRouter)

Vue.component('v-dashboard', require('./components/dashboard/VDashboard.vue'));
Vue.component('minifier-input', require('./components/MinifierInput.vue'));
Vue.component('visits-graph-container', require('./components/VisitsGraphContainer.vue'));
Vue.component('v-copy', require('./components/VCopy.vue'));
Vue.component('vue-navbar', require('./components/VueNavbar.vue'));
Vue.component('public-stats', require('./components/PublicStats.vue'));
Vue.component('v-alert', require('./components/VAlert.vue'));

Vue.directive('remaining', remaining);

const routes = [
	{ path: '/' },
	{ path: '/links/:slug', component: UrlStats}
];

const router = new VueRouter({
	mode: 'history',
	routes,
	base: 'dashboard'
})

// import BootstrapVue  from 'bootstrap-vue/es/components';
let Navbar = require( 'bootstrap-vue/es/components/navbar');

Vue.use(Navbar);

const app = new Vue({
    el: '#app',
	store,
    router
});

window.axios.interceptors.response.use((response) => response,
	(error) => {
		console.log(error);
		return Promise.reject(error);
	});
