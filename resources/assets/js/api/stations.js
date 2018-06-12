/*
    Imports the Roast API URL from the config.
*/
import { ALERT_SYSTEM_CONFIG } from '../config.js';

export default {
    /*
        GET /api/v1/stations
     */
    getStations: function(){
        return axios.get( ALERT_SYSTEM_CONFIG.API_URL + '/stations' );
    },

    getStation: function (id){
        return axios.get(ALERT_SYSTEM_CONFIG.API_URL + '/station/' + id);
    },

    getNets: function () {
        return axios.get(ALERT_SYSTEM_CONFIG.API_URL + '/nets' );
    }

}