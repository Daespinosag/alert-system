import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@alert-system-vue/views/Home';
import About from '@alert-system-vue/views/About';
import PrintAlerts from '@alert-system-vue/views/printAlerts';

Vue.use(VueRouter);

export default new VueRouter({
    routes : [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/printalerts',
            name: 'printalerts',
            //component: () => import(/* webpackPrefetch: true */ '../views/printAlerts')
            component: PrintAlerts
        },
        {
            path: '/about',
            name: 'about',
            component: About
        }
    ]
});