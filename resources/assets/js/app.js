require('./bootstrap');

window.Vue = require('vue');

Vue.component('v-dashboard', require('./components/dashboard/VDashboard.vue'));
Vue.component('minifier-input', require('./components/MinifierInput.vue'));
Vue.component('visits-graph-container', require('./components/VisitsGraphContainer.vue'));
Vue.component('v-copy', require('./components/VCopy.vue'));
Vue.component('vue-navbar', require('./components/VueNavbar.vue'));
import VueRouter from 'vue-router';
import UrlStats from './components/dashboard/UrlStats.vue';

Vue.use(VueRouter)
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
    router
});

// $('#navbarSupportedContent').on('classChange', function() {
//      debugger;
//      console.log('yolo');
// });

// var $div = $("#navbarSupportedContent");
// var observer = new MutationObserver(function(mutations) {
//   mutations.forEach(function(mutation) {
//     if (mutation.attributeName === "class") {
//       var attributeValue = $(mutation.target).prop(mutation.attributeName);
//       console.log("Class attribute changed to:", attributeValue);
//     }
//   });
// });
// observer.observe($div[0], {
//   attributes: true
// });

// $div.addClass('red');
