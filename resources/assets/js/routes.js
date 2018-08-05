/*
|-------------------------------------------------------------------------------
| routes.js
|-------------------------------------------------------------------------------
| Contains all of the routes for the application
*/

/*
    Imports Vue and VueRouter to extend with the routes.
*/
import Vue from 'vue';
import VueRouter from 'vue-router';

/*
    Extends Vue to use Vue Router
*/
Vue.use( VueRouter );

/*
    Makes a new VueRouter that we will use to run all of the routes
    for the app.
*/
/*

 */
export default new VueRouter({
    routes: [
        {
            path: '/',
            name: 'layout',
            component: Vue.component( 'Layout', require( './pages/Layout.vue' ) ),
            children: [
                {
                    path: 'home',
                    name: 'home',
                    component: Vue.component( 'Home', require( './pages/Home.vue' ) )
                },
                {
                    path: 'stations',
                    name: 'stations',
                    component: Vue.component( 'Stations', require( './pages/Stations.vue' ) ),
                    children: [
                        {
                            path: ':id',
                            name: 'station',
                            component: Vue.component( 'Station', require( './pages/Station.vue' ) )
                        },
                    ]
                },
                {
                    path: 'Nets',
                    name: 'Nets',
                    component: Vue.component( 'Nets', require( './pages/Nets.vue' ) )
                },
                {
                    path: 'ConsultAlert',
                    name: 'ConsultAlert',
                    component: Vue.component( 'ConsultAlert', require( './pages/ConsultAlert.vue' ) )
                },
            ]
        }
    ]
});