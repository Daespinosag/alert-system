/*
  Adds the promise polyfill for IE 11
*/
require('es6-promise').polyfill();

/*
    Imports Vue and Vuex
*/
import Vue from 'vue';
import Vuex from 'vuex';

/*
    Initializes Vuex on Vue.
*/
Vue.use( Vuex );

/*
    Imports all of the modules used in the application to build the data store.
*/
import { user } from "./modules/user";
import { alerts } from  "./modules/alerts";
import { typeStation} from "./modules/typeStation";
import { stations } from './modules/stations.js'
import { display} from "./modules/display";
/*
  Exports our data store.
*/

export default new Vuex.Store({
    modules: { user,alerts,typeStation,stations,display }
});