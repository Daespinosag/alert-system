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

        stationsView : 'map'
    },
    actions: {
        loadStations( { commit }, alerts ){
            commit( 'setStationsLoadStatus', 1 );

            return new Promise((resolve, reject) => {
                StationsAPI.getStations(alerts)
                    .then( function( response ){
                        commit( 'setStations', response.data );
                        commit( 'setStationsLoadStatus', 2 );
                        resolve(response);
                    })
                    .catch( function(error){
                        commit( 'setStations', [] );
                        commit( 'setStationsLoadStatus', 3 );
                        reject(error);
                    });
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
        },
        changeStationsView( { commit, state, dispatch }, view ){
            commit( 'setStationsView', view );
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
        },

        setStationsView(state,view){
            state.stationsView = view;
        },

        setShowStationInStations(state,value){
            state.stations[value.position].show = value.show;
        },

        updateStationAlert(state,data){

            if (state.stations[data.position].alertMax < data.value.values.alert) {
                state.stations[data.position].alertMax  = data.value.values.alert;
                state.stations[data.position].iconMax   = (state.stations[data.position].alerts.find(alert => alert.code === 'alert-'+data.value.alert)).icon
            }

            for (let i = 0; i < state.stations[data.position].alerts.length; i++){
                if (state.stations[data.position].alerts[i].code === 'alert-'+data.value.alert){
                    state.stations[data.position].alerts[i].value = data.value.values;
                    state.stations[data.position].dataMaximumAlert   = state.stations[data.position].alerts[i];
                }
            }
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
        },

        getStationsView(state){
            return state.stationsView;
        },
        getStationById : (state) => (stationId) => {
            return state.stations.find(station => station.id === stationId);
        }
    }
}