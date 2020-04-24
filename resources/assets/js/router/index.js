import Vue from 'vue';
import VueRouter from 'vue-router';

import Layout from '@alert-system-vue/views/Layout';
import PrintAlerts from '@alert-system-vue/views/PrintAlerts';

Vue.use(VueRouter);

export default new VueRouter({
    routes : [
        {
            path: '/',
            name: 'Layout',
            component: Layout,
            children: [
                {
                    path: '/PrintAlerts',
                    name: 'PrintAlerts',
                    component: PrintAlerts,
                    /*children: [
                        {
                            path: ':id',
                            name: 'station',
                            component: Vue.component( 'Station', require( './pages/Station.vue' ).default )
                        },
                    ]*/
                }
            ]
        }
    ]
});