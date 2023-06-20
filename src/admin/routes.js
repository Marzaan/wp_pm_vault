import VueRouter from 'vue-router';
import Home from './views/home/index.vue';
import Tools from './views/tools/index.vue';

const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/tools',
        component: Tools
    }
];

const router = new VueRouter({
    routes
});

export default router;