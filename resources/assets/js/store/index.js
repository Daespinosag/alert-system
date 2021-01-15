import Vue from 'vue'
import Vuex from 'vuex'
import VuexORM from '@vuex-orm/core'
import database from './database'

import { userModule } from "./modules/userModule";
import { floodModule } from "./modules/floodModule";
import { landslideModule } from "./modules/landslideModule";
import { displayModule } from "./modules/displayModule";

Vue.use(Vuex)

export default () => new Vuex.Store({
  namespace: true,
  plugins: [VuexORM.install(database)],
  modules: { userModule , floodModule , landslideModule, displayModule },
})

