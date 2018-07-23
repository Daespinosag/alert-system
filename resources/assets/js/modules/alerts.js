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

export const alerts = {
    state: {
        alerts: [],
        alertsLoadStatus: 0,
    },
    actions: {
        loadAlerts( { commit } ){
            commit( 'setAlertsLoadStatus', 1 );

            StationsAPI.getAlerts()
                .then( function( response ){
                    commit( 'setAlerts', response.data );
                    commit( 'setAlertsLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setAlerts', [] );
                    commit( 'setAlertsLoadStatus', 3 );
                });
        }
    },
    mutations: {
        setAlertsLoadStatus( state, status ){
            state.alertsLoadStatus = status;
        },

        setAlerts( state, alerts ){
            state.alerts = alerts;
        }
    },
    getters: {
        getAlertsLoadStatus( state ){
            return state.alertsLoadStatus;
        },

        getAlerts( state ){
            return state.alerts;
        },
    }
}