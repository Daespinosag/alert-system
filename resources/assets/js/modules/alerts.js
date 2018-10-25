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
        loadAlerts( { commit }, permissions ){

            commit( 'setAlertsLoadStatus', 1 );

            return new Promise((resolve, reject) => {
                StationsAPI.getAlerts(permissions)
                    .then( function( response ){
                        commit( 'setAlerts', response.data );
                        commit( 'setAlertsLoadStatus', 2 );
                        resolve(response);

                    })
                    .catch( function(error){
                        commit( 'setAlerts', [] );
                        commit( 'setAlertsLoadStatus', 3 );
                        reject(error);
                    });
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

        getAlertsForForm(state){
            let arr = [];

            for (let i = 0; i < state.alerts.length; i++) {
                arr.push({ 'value': state.alerts[i].code, 'text' : state.alerts[i].name })
            }
            return arr;
        },
        getValidateAlertExistence: (state) => (alertCode) =>{
            for (let i = 0; i < state.alerts.length; i++){
                if (state.alerts[i].code === alertCode){ return true;}
            }
            return false;
        }
    }
}