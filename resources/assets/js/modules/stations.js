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

export const stations = {
    state: {
        stations: [],
        stationsLoadStatus: 0,

        station: {},
        stationLoadStatus: 0,
    },
    actions: {
        loadStations( { commit } ){
            commit( 'setStationsLoadStatus', 1 );

            StationsAPI.getStations()
                .then( function( response ){
                    commit( 'setStations', response.data );
                    commit( 'setStationsLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setStations', [] );
                    commit( 'setStationsLoadStatus', 3 );
                });
        },
        loadStation( { commit }, data ){
            commit( 'setStationLoadStatus', 1 );

            StationsAPI.getStation( data.id )
                .then( function( response ){
                    commit( 'setStation', response.data );
                    commit( 'setStationLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setStation', {} );
                    commit( 'setStationLoadStatus', 3 );
                });
        }
    },
    mutations: {
        setStationsLoadStatus( state, status ){
            state.stationsLoadStatus = status;
        },

        setStations( state, stations ){
            state.stations = stations;
        },

        setStationLoadStatus( state, status ){
            state.stationLoadStatus = status;
        },

        setStation( state, station ){
            state.station = station;
        }
    },
    getters: {
        getStationsLoadStatus( state ){
            return state.stationsLoadStatus;
        },

        getStations( state ){
            return state.stations;
        },

        getStationLoadStatus( state ){
            return state.stationLoadStatus;
        },

        getStation( state ){
            return state.station;
        }
    }
}