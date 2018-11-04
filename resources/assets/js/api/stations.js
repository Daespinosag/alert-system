
import { ALERT_SYSTEM_CONFIG } from '../config.js';

export default {
    /*
        GET /api/v1/stations
     */
    getStations: function(alerts){
        return axios.post( ALERT_SYSTEM_CONFIG.API_URL + '/stations',alerts );
    },

    getStation: function (data){
        return axios.post(ALERT_SYSTEM_CONFIG.API_URL + '/station', data );
    },

    getNets: function () {
        return axios.get(ALERT_SYSTEM_CONFIG.API_URL + '/nets' );
    },

    getAlerts: function (permissions) {
      return axios.post(ALERT_SYSTEM_CONFIG.API_URL + '/alerts', permissions );
    },

    getTypeStation: function () {
        return axios.get(ALERT_SYSTEM_CONFIG.API_URL + '/typeStation' );
    }

}