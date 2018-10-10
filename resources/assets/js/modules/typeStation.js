/*
|-------------------------------------------------------------------------------
| VUEX modules/stations.js
    * estado = 0 -> no se ha iniciado ninguna carga
    * estado = 1 -> la carga ha comenzado
    * estado = 2 -> carga completada con éxito
    * estado = 3 -> carga completada sin éxito
|-------------------------------------------------------------------------------
| The Vuex data store for the stations
*/

import StationsAPI from '../api/stations.js';

export const typeStation = {
    state: {
        typeStation: [],
        typeStationsLoadStatus: 0,
    },
    actions: {
        loadTypeStation( { commit } ){

            commit( 'setTypeStationLoadStatus', 1 );

            return new Promise((resolve, reject) => {

                StationsAPI.getTypeStation()
                    .then( function( response ){
                        commit( 'setTypeStation', response.data );
                        commit( 'setTypeStationLoadStatus', 2 );
                        resolve(response);
                    })
                    .catch( function(error){
                        commit( 'setTypeStation', [] );
                        commit( 'setTypeStationLoadStatus', 3 );
                        reject(error);
                    });
            })

        }
    },
    mutations: {
        setTypeStationLoadStatus( state, status ){
            state.typeStationsLoadStatus = status;
        },

        setTypeStation( state, typeStation ){
            state.typeStation = typeStation;
        }
    },
    getters: {
        getTypeStationLoadStatus( state ){
            return state.typeStationsLoadStatus;
        },

        getTypeStation( state ){
            return state.typeStation;
        },
    }
}