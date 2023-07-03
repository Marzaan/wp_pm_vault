import Vue from 'vue';
import VueRouter from 'vue-router';
import Toasted from 'vue-toasted';
import { showToast } from './toast';
import { ajaxUrl, nonce } from './globals';
import App from './App.vue';
import "./main.scss";

import router from './routes';

Vue.use(VueRouter);
Vue.use(Toasted);

Vue.prototype.$showToast = showToast;
Vue.prototype.$ajaxUrl = ajaxUrl;
Vue.prototype.$nonce = nonce;

new Vue({
    el: '#pm-vault-app',
    router,
    render: h => h(App),
});