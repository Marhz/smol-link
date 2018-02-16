
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('minifier-input', require('./components/MinifierInput.vue'));
Vue.component('visits-graph-container', require('./components/VisitsGraphContainer.vue'));
Vue.component('v-copy', require('./components/VCopy.vue'));
Vue.component('vue-navbar', require('./components/VueNavbar.vue'));

// import BootstrapVue  from 'bootstrap-vue/es/components';
let Navbar = require( 'bootstrap-vue/es/components/navbar');

Vue.use(Navbar);

const app = new Vue({
    el: '#app'
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
