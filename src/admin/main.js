import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './App.vue';
import "./main.scss";

import router from './routes';

Vue.use(VueRouter);

new Vue({
    el: '#pm-vault-app',
    router,
    render: h => h(App),
});