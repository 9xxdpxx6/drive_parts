import Vue from 'vue'
import router from './router'
import App from './App.vue'

require('./bootstrap')

const app = new Vue({
    el: '#app',

    components: {
        App
    },

    router,
});

Vue.filter('formatNumber', function(value) {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
})
