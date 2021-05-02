import loadAPI from "../../api/loadAPI";
import floodAlert from "../models/alerts/flood/floodAlert";
import Net from "../models/alerts/net";
import StationType from "../models/alerts/stationType";
import FloodStation from "../models/alerts/flood/floodStation";
import Basin from "../models/alerts/flood/Basin";

export const floodModule = {
    actions: {
        initFloodInformation( { commit } ){
            return new Promise((resolve, reject) => {
                loadAPI.floodInformation()
                    .then( function( response ){
                        resolve([
                            commit('createModel', { model: Net, data: response.data.nets }),
                            commit('createModel', { model: Basin, data: response.data.basins }),
                            commit('createModel', { model: StationType, data: response.data.stationType }),
                            commit('createModel', { model: floodAlert, data: response.data.alerts }),
                            commit('createModel', { model: FloodStation, data: response.data.stations }),
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