import loadAPI from "../../api/loadAPI";
import Net from "../models/alerts/net";
import Zone from "../models/alerts/landslide/zone";
import StationType from "../models/alerts/stationType";
import LandslideStation from "../models/alerts/landslide/landslideStation";
import LandslideAlert from "../models/alerts/landslide/landslideAlert";

export const landslideModule = {
    actions: {
        initLandslideInformation( { commit }){
            return new Promise((resolve, reject) => {
                loadAPI.landslideInformation()
                    .then( function( response ){
                        resolve([
                            commit('createModel', { model: Net, data: response.data.nets }),
                            commit('createModel', { model: Zone, data: response.data.zones }),
                            commit('createModel', { model: StationType, data: response.data.stationType }),
                            commit('createModel', { model: LandslideAlert, data: response.data.alerts }),
                            commit('createModel', { model: LandslideStation, data: response.data.stations }),
                        ])
                    })
                    .catch( function(error){
                        console.log('Error General')
                        reject(error)
                    })
            })
        }
    },
    mutations: {

    },
    getters:{

    }
}