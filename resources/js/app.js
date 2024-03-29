
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$ = window.jQuery = require('jquery');
window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('categoria', require('./components/Categoria.vue').default);
Vue.component('producto', require('./components/Producto.vue').default);
Vue.component('empleado', require('./components/Empleado.vue').default);
Vue.component('cliente', require('./components/Cliente.vue').default);
Vue.component('rol', require('./components/Rol.vue').default);
Vue.component('user', require('./components/User.vue').default);
Vue.component('factura', require('./components/Factura.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('consultaventa', require('./components/ConsultaVenta.vue').default);
Vue.component('notification', require('./components/Notification.vue').default);
Vue.component('productoagotado', require('./components/productoagotado.vue').default);
Vue.component('compra', require('./components/Compra/ListComponent.vue').default);
Vue.component('NuevaCompra', require('./components/Compra/NewComponent.vue').default);
Vue.component('dailysale', require('./components/DailySale.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data : {
        menu : 0,
        notifications : [],
        //ruta : 'http://localhost:80/WebEuropea/public'
        ruta: ''
    },
    created() {
        let me = this;
        axios.post('notification/get').then(function (response) {
            me.notifications = response.data;
        }).catch(function (error) {
            console.log(error);
        });

        var userid = $('meta[name="userid]').attr('content');

        Echo.private('App.User.' + userid).notification((notification) => {
            me.notifications.unshift(notification);
        });
    }   
});
