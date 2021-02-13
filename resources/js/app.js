import 'bootstrap';
import Vue from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';
import vuelidate from 'vuelidate';
import store from './store';

axios.defaults.baseURL = '/api';

Vue.use(vuelidate)

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
