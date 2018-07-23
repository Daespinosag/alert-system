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

            StationsAPI.getTypeStation()
                .then( function( response ){
                    commit( 'setTypeStation', response.data );
                    commit( 'setTypeStationLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setTypeStation', [] );
                    commit( 'setTypeStationLoadStatus', 3 );
                });
        }
    },
    mutations: {
        setTypeStationLoadStatus( state, status ){
            state.typeStationLoadStatus = status;
        },

        setTypeStation( state, typeStation ){
            state.typeStation = typeStation;
        }
    },
    getters: {
        getTypeStationLoadStatus( state ){
            return state.typeStationLoadStatus;
        },

        getTypeStation( state ){
            return state.typeStation;
        },
    }
}